<?php

namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\lib\WalletBackendService;
use common\models\db\ListAccountPurchase;
use common\models\Order;
use common\models\PaymentTransaction;
use common\models\Product;
use common\models\User;
use common\models\weshop\FormPurchaseItem;
use common\modelsMongo\ChatMongoWs;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\wallet\WalletService;
use Yii;

class PurchaseServiceController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['list-account'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['send-notify-changing', 'confirm-changing-price'],
                'roles' => $this->getAllRoles(true),

            ],
        ];
    }

    public function verbs()
    {
        return [
            'list-account' => ['GET'],
            'send-notify-changing' => ['POST'],
            'confirm-changing-price' => ['POST'],
        ];
    }

    public function actionListAccount()
    {
        $type = Yii::$app->request->get('type', 'all');
        $account = ListAccountPurchase::find()->where(['active' => 1]);
        if ($type !== 'all') {
            $account->andWhere(['type' => strtolower($type)]);
        }
        $account = $account->asArray()->all();
        return $this->response(true, "Success", $account);
    }

    public function actionSendNotifyChanging()
    {
        /** @var  $exRate  \common\components\ExchangeRate */
        $exRate = \Yii::$app->exRate;
        $tran = Yii::$app->db->beginTransaction();
        try{
            $emailPrice = Yii::$app->request->post('emailPrice', false);
//        $emailFragile = Yii::$app->request->post('emailFragile',false);
            $orders = Yii::$app->request->post('cart', false);
            $message = "Kính chào quý khách";
            $messageOrder = "";
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            if ($user) {
                foreach ($orders as $order) {
                    $messageProduct = "";
                    $amount = 0;
                    foreach ($order['products'] as $product) {
                        $form = new FormPurchaseItem();
                        $form->setAttributes($product, false);
                        $modelProduct = Product::findOne($form->id);
                        $mss = " tại mã sản phẩm <b>" . (strtolower($form->ItemType) == 'ebay' ? $form->ParentSku : $form->sku) . "</b>";
                        $amountChange = 0;
                        if ($modelProduct) {
                            if (round($modelProduct->unitPrice->amount, 0) != round($form->price_purchase, 0)) {
                                $amountChange = round($form->price_purchase - $modelProduct->unitPrice->amount, 2);
                                $mss .= ' có sự thay đổi về <b>giá</b>';
                                $mss .= $amountChange > 0 ? ' tăng thêm <b>' . abs($amountChange) . '$</b>' : ' giảm đi <b>' . abs($amountChange) . '$</b>';
                            }
                            if (round($modelProduct->usShippingFee->amount, 0) != round($form->us_ship_purchase, 0)) {
                                $mss .= $amountChange != 0 ? ', và có sự thay đổi về <b>phí ship</b>' : ' có sự thay đổi về <b>phí ship</b>';
                                $amountChange = round($form->us_ship_purchase - $modelProduct->usShippingFee->amount, 2);
                                $mss .= $amountChange > 0 ? ' tăng thêm <b>' . abs($amountChange) . '$</b>' : ' giảm đi <b>' . abs($amountChange) . '$</b>';
                            }
                            if (round($modelProduct->usTax->amount, 0) != round($form->us_tax_purchase, 0)) {
                                $mss .= $amountChange != 0 ? ', và có sự thay đổi về <b>phí tax</b>' : ' có sự thay đổi về <b>phí tax</b>';
                                $amountChange = round($form->us_tax_purchase - $modelProduct->usTax->amount, 2);
                                $mss .= $amountChange > 0 ? ' tăng thêm <b>' . abs($amountChange) . '$</b>' : ' giảm đi <b>' . abs($amountChange) . '$</b>';
                            }
                            if ($amountChange != 0) {
                                $modelProduct->price_purchase = $form->price_purchase;
                                $modelProduct->shipping_fee_purchase = $form->us_ship_purchase;
                                $modelProduct->tax_fee_purchase = $form->us_tax_purchase;
                                $order = $modelProduct->order;
                                if($modelProduct->price_purchase){
                                    $fee = $modelProduct->unitPrice;
                                    $check = true;
                                    $old_local_amount = $fee->local_amount;
                                    $old_amount = $fee->amount;
                                    $fee->amount = $modelProduct->price_purchase;
                                    $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);

                                    $modelProduct->total_price_amount_local += $fee->local_amount - $old_local_amount ;
                                    $modelProduct->updated_at = time();
                                    $modelProduct->price_purchase = null;
                                    $order->updated_at = time();

                                    $order->total_price_amount_origin += $fee->amount - $old_amount;
//                            $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_amount_local += $fee->local_amount - $old_local_amount;
                                    $fee->save(0);
                                }
                                if($modelProduct->shipping_fee_purchase){
                                    $check = true;
                                    $fee = $modelProduct->usShippingFee;
                                    $old_local_amount = $fee->local_amount;
                                    $old_amount = $fee->amount;
                                    $fee->amount = $modelProduct->shipping_fee_purchase;
                                    $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                                    $order = $modelProduct->order;

                                    $modelProduct->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                                    $modelProduct->updated_at = time();
                                    $modelProduct->shipping_fee_purchase = null;
                                    $order->updated_at = time();

                                    $order->total_origin_shipping_fee_local += $fee->amount - $old_amount;
                                    $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_amount_local += $fee->local_amount - $old_local_amount;
                                    $fee->save(0);
                                }
                                if($modelProduct->tax_fee_purchase){
                                    $check = true;
                                    $fee = $modelProduct->usTax;
                                    $old_local_amount = $fee->local_amount;
                                    $old_amount = $fee->amount;
                                    $fee->amount = $modelProduct->tax_fee_purchase;
                                    $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                                    $order = $modelProduct->order;

                                    $modelProduct->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                                    $modelProduct->updated_at = time();
                                    $modelProduct->tax_fee_purchase = null;
                                    $order->updated_at = time();

                                    $order->total_origin_tax_fee_local += $fee->amount - $old_amount;
                                    $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                                    $order->total_amount_local += $fee->local_amount - $old_local_amount;

                                    $fee->save(0);
                                }
                                $order->save(0);
                                $modelProduct->save();
                                $modelProduct->save();
                                $messageProduct .= $messageProduct ? '. Và' . $mss : $mss;
                            }
                        }
                        $amount = $amount + $amountChange;
                    }
                    if ($messageProduct != "" && $amount > 0) {
                        $orderDb = Order::findOne(['ordercode' => $order['ordercode']]);
                        /** @var PaymentTransaction[] $paymentTransactions */
                        $paymentTransactions = PaymentTransaction::find()
                            ->where([
                                'order_code' => $orderDb->ordercode,
                                'transaction_status' => [
                                    PaymentTransaction::TRANSACTION_STATUS_CREATED,
                                    PaymentTransaction::TRANSACTION_STATUS_QUEUED
                                ],
                                'transaction_type' => PaymentTransaction::TRANSACTION_ADDFEE
                            ])->all();
                        $listIdChange = [];
                        $transaction_description = '';
                        $amount = $exRate->usdToVnd($amount,23500);
                        foreach ($paymentTransactions as $item){
                            $listIdChange[] = $item->id;
                            $amount += $item->transaction_amount_local;
                            $transaction_description = $transaction_description ? $transaction_description.'<br><b>*</b> '.$item->transaction_description : $item->transaction_description;
                        }
                        Yii::debug($amount,'amount_change');
                        $paymentTransaction = new PaymentTransaction();
                        $paymentTransaction->store_id = $orderDb->store_id;
                        $paymentTransaction->customer_id = $orderDb->customer_id;
                        $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_ADDFEE;
                        $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_QUEUED;
                        $paymentTransaction->transaction_customer_name = $orderDb->receiver_name;
                        $paymentTransaction->transaction_customer_email = $orderDb->receiver_email;
                        $paymentTransaction->transaction_customer_phone = $orderDb->receiver_phone;
                        $paymentTransaction->transaction_customer_address = $orderDb->receiver_address;
                        $paymentTransaction->transaction_customer_city = $orderDb->receiver_province_name;
                        $paymentTransaction->transaction_customer_postcode = $orderDb->receiver_post_code;
                        $paymentTransaction->transaction_customer_district = $orderDb->receiver_district_name;
                        $paymentTransaction->transaction_customer_country = $orderDb->receiver_country_name;
                        $paymentTransaction->order_code = $orderDb->ordercode;
                        $paymentTransaction->shipping = 0;
                        $paymentTransaction->payment_type = PaymentTransaction::PAYMENT_TYPE_ADDFEE;
                        $paymentTransaction->carts = '';
                        $paymentTransaction->transaction_description = $transaction_description ? $transaction_description.'<br>'.$messageProduct : $messageProduct;
                        $paymentTransaction->total_discount_amount = 0;
                        $paymentTransaction->before_discount_amount_local = $amount;
                        $paymentTransaction->transaction_amount_local = $amount;
                        $paymentTransaction->payment_provider = 'WS WALLET';
                        $paymentTransaction->payment_method = 'WALLET_WESHOP';
                        $paymentTransaction->payment_bank_code = 'WALLET_WESHOP';
                        $paymentTransaction->created_at = time();
                        $paymentTransaction->save(0);
                        $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                        $paymentTransaction->save(0);
                        PaymentTransaction::updateAll(
                            ['transaction_status' => PaymentTransaction::TRANSACTION_STATUS_REPLACED,'transaction_reference_code' => $paymentTransaction->transaction_code],
                            ['id' => $listIdChange]);
                        $messageOrder = ". Mã đơn hàng <b>" . $orderDb->ordercode . "</b>" . $messageProduct . ".<br>Quý khách có muốn tiếp tục đặt đơn này không ạ.<br>Nếu có vui lòng xác nhận với WeShop sớm nhất để tránh sự cố hết hàng hoặc tăng giá tiếp tục sảy ra.";
                        $messageOrder .= "<br>Bạn có thể thực hiện thanh toán ngay <a href='/my-weshop/addfee-$paymentTransaction->transaction_code.html'>tại đây</a>.";
                        $messageOrder .= "<br>Xin cảm ơn.";
                        if ($emailPrice) {
                            //#ToDo Gửi mail thông báo thay đổi về giá
                        }
                        //#ToDo thông báo chat thay đổi về giá
                        $model = new ChatMongoWs();
                        $_rest_data = ["ChatMongoWs" => [
                            "success" => true,
                            "message" => $message . $messageOrder,
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
                        if (!$model->load($_rest_data) || !$model->save()) {
                            $tran->rollBack();
                            Yii::error($model->errors);
                            return $this->response(false, "Gửi thông báo thất bại");
                        }
                    }
                }
                if ($messageOrder != "") {
                    $message .= $messageOrder;
                    $tran->commit();
                    return $this->response(true, "Đã gửi thông báo tới khách hàng!", $message);
                } else {
                    $tran->rollBack();
                    return $this->response(false, "Không có thay đổi gì về số tiền, vui lòng kiểm tra lại.");
                }
            } else {
                return $this->response(false, "Vui lòng đăng nhập.");
            }
        }catch (\Exception $exception){
            $tran->rollBack();
            Yii::error($exception);
            return $this->response(false, "Tạo thông báo thất bại");
        }
    }

    public function actionConfirmChangingPrice()
    {
        $orders = Yii::$app->request->post('cart', false);
        /** @var  $exRate  \common\components\ExchangeRate */
        $exRate = \Yii::$app->exRate;
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if ($user) {
            $tran = Yii::$app->db->beginTransaction();
            try{
                foreach ($orders as $order) {
                    foreach ($order['products'] as $product) {
                        $model = Product::findOne($product['id']);
                        if ($model){
                            $check = false;
                            if($model->price_purchase){
                                $check = true;
                                $model->price_purchase = null;
                            }
                            if($model->shipping_fee_purchase){
                                $check = true;
                                $model->shipping_fee_purchase = null;
                            }
                            if($model->tax_fee_purchase){
                                $check = true;
                                $model->tax_fee_purchase = null;
                            }
                            $model->save();
                            if($check){
                                Yii::$app->wsLog->push('order','updateFee', null, [
                                    'id' => $model->id,
                                    'request' => "Customer confirm changing price",
                                    'response' => "Customer confirm changing price success"
                                ]);
                            }
                        }
                    }
                    /** @var PaymentTransaction[] $transactionPayments */
                    $transactionPayments = PaymentTransaction::find()->where([
                        'order_code' => $order['ordercode'],
                        'transaction_status' => [
                            PaymentTransaction::TRANSACTION_STATUS_CREATED,
                            PaymentTransaction::TRANSACTION_STATUS_QUEUED
                        ],
                        'transaction_type' => PaymentTransaction::TRANSACTION_ADDFEE
                    ])->all();
                    foreach ($transactionPayments as $payment){
                        $payment->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                        $payment->save(false);
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
                            return $this->response(false, "Thất bại.",$result);
                        }
                        $orderDb = Order::findOne(['ordercode' => $payment->order_code]);
                        if($orderDb){
                            $orderDb->total_paid_amount_local = $orderDb->total_paid_amount_local + $payment->transaction_amount_local;
                            $orderDb->save(false);
                        }
                        //#ToDo thông báo chat thay đổi về giá
                        $model = new ChatMongoWs();
                        $_rest_data = ["ChatMongoWs" => [
                            "success" => true,
                            "message" => 'Đã tự động thanh toán thu thêm giao dịch '.$payment->transaction_code,
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
                    }
                }
                $tran->commit();
                return $this->response(true, "Đã xác nhận thành công.");
            }catch (\Exception $exception){
                $tran->rollBack();
                Yii::error($exception);
                return $this->response(false, "Xác nhận thất bại");
            }
        }
        return $this->response(false, "Vui lòng đăng nhập.");

    }

}
