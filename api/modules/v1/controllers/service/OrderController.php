<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\lib\WalletBackendService;
use common\models\Order;
use common\models\PaymentTransaction;
use common\models\Product;
use common\modelsMongo\ChatMongoWs;
use frontend\modules\payment\PaymentService;
use Yii;

class OrderController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','update','update-arrears','confirm-change-price'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'update' => ['PUT'],
            'create' => ['POST'],
            'update-arrears' => ['POST'],
            'confirm-change-price' => ['POST'],
        ];
    }

    public function actionUpdate($id){
        $model = Order::findOne($id);
        if(!$model){
            return $this->response(false,'Update order false!');
        }
        if ($model->current_status == 'PURCHASED') {
            $model->current_status = 'SELLER_SHIPPED';
            $model->seller_shipped = time();
        } else if ($model->current_status == 'SELLER_SHIPPED') {
            $model->current_status = 'US_RECEIVED';
            $model->stockin_us = time();
        } else if ($model->current_status == 'US_RECEIVED') {
            $model->current_status = 'US_SENDING';
            $model->stockout_us = time();
        } else if ($model->current_status == 'US_SENDING') {
            $model->current_status = 'LOCAL_RECEIVED';
            $model->stockin_local = time();
        } else if ($model->current_status == 'LOCAL_RECEIVED') {
            $model->current_status = 'DELIVERING';
            $model->stockout_local = time();
        } else if ($model->current_status == 'DELIVERING') {
            $model->current_status = 'AT_CUSTOMER';
            $model->at_customer = time();
        }
        $model->save();
        return $this->response(true, 'Update Success');
    }
    public function actionUpdateArrears(){
        $order = Order::findOne(['ordercode' => \Yii::$app->request->post('order_code')]);
        $user = Yii::$app->user->getIdentity();
        if($order){
            /** @var PaymentTransaction[] $transactionPayments */
            $transactionPayments = PaymentTransaction::find()->where([
                'order_code' => $order->ordercode,
                'transaction_status' => [
                    PaymentTransaction::TRANSACTION_STATUS_CREATED,
                    PaymentTransaction::TRANSACTION_STATUS_QUEUED
                ],
                'transaction_type' => PaymentTransaction::TRANSACTION_ADDFEE
            ])->all();
            foreach ($transactionPayments as $payment){
                $tran = Yii::$app->db->beginTransaction();
                try{
                    $payment->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                    $payment->save(false);
                    $order->total_paid_amount_local = $order->total_paid_amount_local + $payment->transaction_amount_local;
                    $order->save(false);
                    //#ToDo thông báo chat thay đổi về giá
                    $model = new ChatMongoWs();
                    $_rest_data = ["ChatMongoWs" => [
                        "success" => true,
                        "message" => 'Đã tự động thanh toán thu thêm giao dịch '.$payment->transaction_code. ' của order '.$order->ordercode,
                        "date" => Yii::$app->getFormatter()->asDatetime('now'),
                        "user_id" => $user->id,
                        "user_email" => $user->email,
                        "user_name" => $user->username,
                        "user_app" => null,
                        "user_request_suorce" => 'BACK_END',  // "APP/FRONTEND/BACK_END"
                        "request_ip" => Yii::$app->getRequest()->getUserIP(), // Todo : set
                        "user_avatars" => null,
                        "Order_path" => $order['ordercode'],
                        "is_send_email_to_customer" => null,
                        "type_chat" => 'WS_CUSTOMER', // 'TYPE_CHAT : GROUP_WS/WS_CUSTOMER // Todo : set
                        "is_customer_vew" => null,
                        "is_employee_vew" => null
                    ]];
                    $model->load($_rest_data);
                    $model->save();
                    Yii::info($payment->transaction_status,'payment_status');
                    $WalletS = new WalletBackendService();
                    $WalletS->payment_transaction = $payment->transaction_code;
                    $WalletS->payment_method = $payment->payment_method;
                    $WalletS->payment_provider = $payment->payment_provider;
                    $WalletS->bank_code = $payment->payment_bank_code;
                    $WalletS->total_amount = intval($payment->transaction_amount_local);
                    $WalletS->type = WalletBackendService::TYPE_PAY_ADDFEE;
                    $WalletS->customer_id = $payment->customer_id;
                    $WalletS->description = $payment->transaction_description;
                    $result = $WalletS->createSafePaymentTransaction();
                    if(!$result['success']){
                        $tran->rollBack();
                        continue;
                    }
                    $tran->commit();
                }catch (\Exception $exception){
                    $tran->rollBack();
                }
            }
            if(($amountAddfee = $order->total_final_amount_local - $order->total_paid_amount_local) > 0){
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->store_id = $order->store_id;
                $paymentTransaction->customer_id = $order->customer_id;
                $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_ADDFEE;
                $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_QUEUED;
                $paymentTransaction->transaction_customer_name = $order->receiver_name;
                $paymentTransaction->transaction_customer_email = $order->receiver_email;
                $paymentTransaction->transaction_customer_phone = $order->receiver_phone;
                $paymentTransaction->transaction_customer_address = $order->receiver_address;
                $paymentTransaction->transaction_customer_city = $order->receiver_province_name;
                $paymentTransaction->transaction_customer_postcode = $order->receiver_post_code;
                $paymentTransaction->transaction_customer_district = $order->receiver_district_name;
                $paymentTransaction->transaction_customer_country = $order->receiver_country_name;
                $paymentTransaction->order_code = $order->ordercode;
                $paymentTransaction->shipping = 0;
                $paymentTransaction->payment_type = PaymentTransaction::PAYMENT_TYPE_ADDFEE;
                $paymentTransaction->carts = '';
                $paymentTransaction->transaction_description = "Thu thêm lệnh tiền thanh toán order";
                $paymentTransaction->total_discount_amount = 0;
                $paymentTransaction->before_discount_amount_local = $amountAddfee;
                $paymentTransaction->transaction_amount_local = $amountAddfee;
                $paymentTransaction->payment_provider = 'WS WALLET';
                $paymentTransaction->payment_method = 'WALLET_WESHOP';
                $paymentTransaction->payment_bank_code = 'WALLET_WESHOP';
                $paymentTransaction->created_at = time();
                $paymentTransaction->save(0);
                $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                $paymentTransaction->save(0);
                $tran = Yii::$app->db->beginTransaction();
                try{
                    $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                    $paymentTransaction->save(false);
                    Yii::info($paymentTransaction->transaction_status,'payment_status');
                    $WalletS = new WalletBackendService();
                    $WalletS->payment_transaction = $paymentTransaction->transaction_code;
                    $WalletS->payment_method = $paymentTransaction->payment_method;
                    $WalletS->payment_provider = $paymentTransaction->payment_provider;
                    $WalletS->bank_code = $paymentTransaction->payment_bank_code;
                    $WalletS->total_amount = intval($paymentTransaction->transaction_amount_local);
                    $WalletS->type = WalletBackendService::TYPE_PAY_ADDFEE;
                    $WalletS->customer_id = $paymentTransaction->customer_id;
                    $WalletS->description = $paymentTransaction->transaction_description;
                    $result = $WalletS->createSafePaymentTransaction();
                    if(!$result['success']){
                        $tran->rollBack();
                    }
                    $order->total_paid_amount_local = $order->total_paid_amount_local + $paymentTransaction->transaction_amount_local;
                    $order->save(false);
                    //#ToDo thông báo chat thay đổi về giá
                    $model = new ChatMongoWs();
                    $_rest_data = ["ChatMongoWs" => [
                        "success" => true,
                        "message" => 'Đã tự động thanh toán thu thêm giao dịch '.$paymentTransaction->transaction_code. ' của order '.$order->ordercode,
                        "date" => Yii::$app->getFormatter()->asDatetime('now'),
                        "user_id" => $user->id,
                        "user_email" => $user->email,
                        "user_name" => $user->username,
                        "user_app" => null,
                        "user_request_suorce" => 'BACK_END',  // "APP/FRONTEND/BACK_END"
                        "request_ip" => Yii::$app->getRequest()->getUserIP(), // Todo : set
                        "user_avatars" => null,
                        "Order_path" => $order['ordercode'],
                        "is_send_email_to_customer" => null,
                        "type_chat" => 'WS_CUSTOMER', // 'TYPE_CHAT : GROUP_WS/WS_CUSTOMER // Todo : set
                        "is_customer_vew" => null,
                        "is_employee_vew" => null
                    ]];
                    $model->load($_rest_data);
                    $model->save();
                    $tran->commit();
                }catch (\Exception $exception){
                    $tran->rollBack();
                }
            }
            return $this->response(true, 'Update Success');
        }
        return $this->response(false, 'Cannot find product.');
    }

    public function actionConfirmChangePrice() {
        $order_id = Yii::$app->request->post('order_id');
        $product_id = Yii::$app->request->post('product_id');
        $order = Order::findOne($order_id);
        if(!$order_id || !$order){
            return $this->response(false, 'Order not found.');
        }
        /** @var  $exRate  \common\components\ExchangeRate */
        $exRate = \Yii::$app->exRate;
        $confirm_change_price = Order::STATUS_CONFIRMED_CHANGE_PRICE;
        foreach ($order->products as $model) {
            if($product_id && $product_id != $model->id && $model->confirm_change_price != Product::STATUS_CONFIRMED_CHANGE_PRICE){
                $confirm_change_price = Order::STATUS_NEED_CONFIRM_CHANGE_PRICE;
                continue;
            }
            $check = false;
            if($model->price_purchase && $model->price_purchase > $model->unitPrice->amount){
                $fee = $model->unitPrice;
                $check = true;
                $old_local_amount = $fee->local_amount;
                $old_amount = $fee->amount;
                $fee->amount = $model->price_purchase;
                $fee->local_amount = $exRate->usdToVnd($model->price_purchase,23500);
                $model->total_price_amount_local += $fee->local_amount - $old_local_amount ;
                $model->price_amount_local += $fee->local_amount - $old_local_amount ;
                $model->price_amount_origin += $fee->amount - $old_amount;
                $model->updated_at = time();
                $order->updated_at = time();
                $order->total_price_amount_origin += $fee->amount - $old_amount;
                $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_origin_fee_local += $fee->local_amount - $old_local_amount;
                $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_amount_local += $fee->local_amount - $old_local_amount;
                $fee->save(0);
                $model->price_purchase = null;
            }
            if($model->shipping_fee_purchase && $model->shipping_fee_purchase > $model->usShippingFee->amount){
                $check = true;
                $fee = $model->usShippingFee;
                $old_local_amount = $fee->local_amount;
                $old_amount = $fee->amount;
                $fee->amount = $model->shipping_fee_purchase;
                $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                $model->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                $model->updated_at = time();

                $order->updated_at = time();
                $order->total_origin_shipping_fee_local += $fee->amount - $old_amount;
                $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_origin_fee_local += $fee->local_amount - $old_local_amount;
                $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_amount_local += $fee->local_amount - $old_local_amount;
                $fee->save(0);
                $model->shipping_fee_purchase = null;
            }
            if($model->tax_fee_purchase && $model->tax_fee_purchase > $model->usTax->amount){
                $check = true;
                $fee = $model->usTax;
                $old_local_amount = $fee->local_amount;
                $old_amount = $fee->amount;
                $fee->amount = $model->tax_fee_purchase;
                $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);

                $model->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                $model->updated_at = time();

                $order->updated_at = time();
                $order->total_origin_tax_fee_local += $fee->amount - $old_amount;
                $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                $order->total_origin_fee_local += $fee->local_amount - $old_local_amount;
                $order->total_amount_local += $fee->local_amount - $old_local_amount;
                $fee->save(0);
                $model->tax_fee_purchase = null;
            }
            $model->confirm_change_price = Product::STATUS_CONFIRMED_CHANGE_PRICE;
            $order->confirm_change_price = Order::STATUS_CONFIRMED_CHANGE_PRICE;
            $order->save(false);
            $model->save();
            if($check){
                ChatMongoWs::SendMessage('Xác nhận tăng giá từ khách hàng cho sản phẩm : '.$model->sku, $order['ordercode']);
                Yii::$app->wsLog->push('order','updateFee', null, [
                    'id' => $model->id,
                    'request' => "Customer confirm changing price",
                    'response' => "Customer confirm changing price success"
                ]);
            }
        }
        $order->confirm_change_price = $confirm_change_price;
        $order->save();
        return $this->response(true, 'Confirm success.');
    }
}