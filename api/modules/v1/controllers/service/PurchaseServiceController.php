<?php

namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\components\ExchangeRate;
use common\models\db\ListAccountPurchase;
use common\models\PaymentTransaction;
use common\models\Product;
use common\models\Order;
use common\models\User;
use common\models\weshop\FormPurchaseItem;
use common\modelsMongo\ChatMongoWs;
use frontend\modules\payment\Payment;
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
                                $modelProduct->save();
                                $messageProduct .= $messageProduct ? '. Và' . $mss : $mss;
                            }
                        }
                    }
                    if ($messageProduct != "") {
                        $messageOrder = ". Mã đơn hàng <b>" . $order['ordercode'] . "</b>" . $messageProduct . ".<br>Quý khách có muốn tiếp tục đặt đơn này không ạ.<br>Nếu có vui lòng xác nhận với WeShop sớm nhất để tránh sự cố hết hàng hoặc tăng giá tiếp tục sảy ra.<br>Xin cảm ơn.";
                        $orderDb = Order::findOne(['ordercode' => $order['ordercode']]);
                        $paymentTransaction = new PaymentTransaction();
                        $paymentTransaction->store_id = $orderDb->store_id;
                        $paymentTransaction->customer_id = $orderDb->customer_id;
                        $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_ADDFEE;
                        $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_CREATED;
                        $paymentTransaction->transaction_customer_name = $orderDb->receiver_name;
                        $paymentTransaction->transaction_customer_email = $orderDb->receiver_email;
                        $paymentTransaction->transaction_customer_phone = $orderDb->receiver_phone;
                        $paymentTransaction->transaction_customer_address = $orderDb->receiver_address;
                        $paymentTransaction->transaction_customer_city = $orderDb->receiver_address;
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
                            $messageOrder = "";
                        }
                    }
                }
                if ($messageOrder != "") {
                    $message .= $messageOrder;
                    $tran->commit();
                    return $this->response(true, "Đã gửi thông báo tới khách hàng!", $message);
                } else {
                    return $this->response(false, "Không có thay đổi gì về số tiền, vui lòng kiểm tra lại.");
                }
            } else {
                return $this->response(false, "Vui lòng đăng nhập.");
            }
        }catch (\Exception $exception){
            $tran->rollBack();
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
            foreach ($orders as $order) {
                foreach ($order['products'] as $product) {
                    $model = Product::findOne($product['id']);
                    if ($model){
                        $check = false;
                        if($model->price_purchase){
                            $check = true;
                            $fee = $model->unitPrice;
                            $old_local_amount = $fee->local_amount;
                            $old_amount = $fee->amount;
                            $fee->amount = $model->price_purchase;
                            $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                            $fee->save(false);
                            $order = $model->order;

                            $model->total_price_amount_local += $fee->local_amount - $old_local_amount ;
                            $model->updated_at = time();
                            $model->price_purchase = null;
                            $model->save();
                            $order->updated_at = time();

                            $order->total_price_amount_origin += $fee->amount - $old_amount;
//                            $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_amount_local += $fee->local_amount - $old_local_amount;

                            $order->save(0);
                        }
                        if($model->shipping_fee_purchase){
                            $check = true;
                            $fee = $model->usShippingFee;
                            $old_local_amount = $fee->local_amount;
                            $old_amount = $fee->amount;
                            $fee->amount = $model->shipping_fee_purchase;
                            $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                            $fee->save(false);
                            $order = $model->order;

                            $model->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                            $model->updated_at = time();
                            $model->shipping_fee_purchase = null;
                            $model->save();
                            $order->updated_at = time();

                            $order->total_origin_shipping_fee_local += $fee->amount - $old_amount;
                            $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_amount_local += $fee->local_amount - $old_local_amount;

                            $order->save(0);
                        }
                        if($model->tax_fee_purchase){
                            $check = true;
                            $fee = $model->usTax;
                            $old_local_amount = $fee->local_amount;
                            $old_amount = $fee->amount;
                            $fee->amount = $model->tax_fee_purchase;
                            $fee->local_amount = $exRate->usdToVnd($fee->amount,23500);
                            $fee->save(false);
                            $order = $model->order;

                            $model->total_fee_product_local += $fee->local_amount - $old_local_amount ;
                            $model->updated_at = time();
                            $model->tax_fee_purchase = null;
                            $model->save();
                            $order->updated_at = time();

                            $order->total_origin_tax_fee_local += $fee->amount - $old_amount;
                            $order->total_fee_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_final_amount_local += $fee->local_amount - $old_local_amount;
                            $order->total_amount_local += $fee->local_amount - $old_local_amount;

                            $order->save(0);
                        }
                        if($check){
                            Yii::$app->wsLog->push('order','updateFee', null, [
                                'id' => $model->id,
                                'request' => "Customer confirm changing price",
                                'response' => "Customer confirm changing price success"
                            ]);
                        }
                    }
                }
            }
            return $this->response(true, "Đã xác nhận thành công.");
        }
        return $this->response(false, "Vui lòng đăng nhập.");

    }

}
