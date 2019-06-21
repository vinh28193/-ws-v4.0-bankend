<?php


namespace frontend\modules\payment\controllers;


use common\helpers\WeshopHelper;
use common\models\Address;
use common\models\Order;
use common\models\db\TargetAdditionalFee;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\nganluong\ver3_2\NganLuongClient;
use common\promotion\PromotionResponse;
use frontend\modules\payment\models\ShippingForm;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Response;
use frontend\modules\payment\Payment;

class PaymentController extends BasePaymentController
{

    public function actionProcess()
    {
        $start = microtime(true);
        $now = Yii::$app->getFormatter()->asDatetime('now');
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();


        $shippingParams = [
            'buyer_name' => $shippingForm->buyer_name,
            'buyer_address' => $shippingForm->buyer_address,
            'buyer_email' => $shippingForm->buyer_email,
            'buyer_phone' => $shippingForm->buyer_phone,
            'buyer_country_id' => $this->storeManager->store->country_id,
            'buyer_province_id' => $shippingForm->buyer_province_id,
            'buyer_district_id' => $shippingForm->buyer_district_id,
            'buyer_post_code' => $shippingForm->buyer_post_code,
            'buyer_province_name' => $shippingForm->getReceiverDistrictName(),
            'buyer_district_name' => $shippingForm->getReceiverDistrictName(),
            'buyer_country_name' => $this->storeManager->store->country_name,
            'receiver_name' => $shippingForm->receiver_name,
            'receiver_address' => $shippingForm->receiver_address,
            'receiver_phone' => $shippingForm->receiver_phone,
            'receiver_country_id' => $this->storeManager->store->country_id,
            'receiver_province_id' => $shippingForm->receiver_province_id,
            'receiver_district_id' => $shippingForm->receiver_district_id,
            'receiver_post_code' => $shippingForm->receiver_post_code,
            'receiver_province_name' => $shippingForm->getReceiverDistrictName(),
            'receiver_district_name' => $shippingForm->getReceiverDistrictName(),
            'receiver_country_name' => $this->storeManager->store->country_name,
            'receiver_address_id' => $shippingForm->receiver_address_id,
        ];
        if ($shippingForm->buyer_address_id !== null && ($buyer = Address::findOne($shippingForm->buyer_address_id)) !== null) {
            $shippingParams['buyer_name'] = implode(' ', [$buyer->first_name, $buyer->last_name]);
            $shippingParams['buyer_address'] = $buyer->address;
            $shippingParams['buyer_email'] = $buyer->email;
            $shippingParams['buyer_phone'] = $buyer->phone;
            $shippingParams['buyer_province_id'] = $buyer->province_id;
            $shippingParams['buyer_district_id'] = $buyer->district_id;
            $shippingParams['buyer_province_name'] = $buyer->province_name;
            $shippingParams['buyer_district_name'] = $buyer->district_name;
            $shippingParams['buyer_post_code'] = $buyer->post_code;
            if ((int)$shippingForm->enable_receiver === ShippingForm::NO) {
                $shippingParams['receiver_name'] = $buyer->address;
                $shippingParams['receiver_address'] = $buyer->phone;
                $shippingParams['receiver_phone'] = $buyer->province_id;
                $shippingParams['receiver_province_id'] = $buyer->district_id;
                $shippingParams['receiver_district_id'] = $buyer->post_code;
            }

        }
        if ($shippingForm->other_receiver !== false) {
            if ((int)$shippingForm->enable_receiver === ShippingForm::YES) {
                $shippingParams['receiver_name'] = $shippingForm->receiver_name;
                $shippingParams['receiver_address'] = $shippingForm->receiver_address;
                $shippingParams['receiver_phone'] = $shippingForm->receiver_phone;
                $shippingParams['receiver_province_id'] = $shippingForm->receiver_province_id;
                $shippingParams['receiver_province_name'] = $shippingForm->getReceiverProvinceName();
                $shippingParams['receiver_district_id'] = $shippingForm->receiver_district_id;
                $shippingParams['receiver_district_name'] = $shippingForm->getReceiverDistrictName();
                $shippingParams['receiver_post_code'] = $shippingForm->receiver_post_code;
                $shippingParams['receiver_country_id'] = $shippingForm->receiver_country_id;
                $shippingParams['receiver_country_name'] = $this->storeManager->store->country_name;
            } else if ($shippingForm->receiver_address_id !== null && ($receiver = Address::findOne($shippingForm->receiver_address_id)) !== null) {
                $shippingParams['receiver_name'] = $receiver->address;
                $shippingParams['receiver_address'] = $receiver->phone;
                $shippingParams['receiver_phone'] = $receiver->province_id;
                $shippingParams['receiver_province_id'] = $receiver->district_id;
                $shippingParams['receiver_district_id'] = $receiver->post_code;
                $shippingParams['receiver_province_name'] = $receiver->province_name;
                $shippingParams['receiver_district_name'] = $receiver->district_name;
            }
        }

        $payment->customer_name = $shippingForm->buyer_name;
        $payment->customer_email = $shippingForm->buyer_email;
        $payment->customer_phone = $shippingForm->buyer_phone;
        $payment->customer_address = $shippingForm->buyer_address;
        $payment->customer_city = $shippingForm->getReceiverProvinceName();
        $payment->customer_postcode = $shippingForm->buyer_post_code;
        $payment->customer_district = $shippingForm->getBuyerDistrictName();
        $payment->customer_country = $this->storeManager->store->country_name;
        $payment->createTransactionCode();
        /* @var $results PromotionResponse */
        $payment->checkPromotion();
        $transaction = Order::getDb()->beginTransaction();
        try {
            foreach ($payment->getOrders() as $key => $orderPayment) {
                $order = clone $orderPayment;
                $order->setAttributes($shippingParams);
                $orderTotalDiscount = 0;
                unset($order['products']);
                unset($order['seller']);
                unset($order['saleSupport']);
                // 1 order
                $order->type_order = Order::TYPE_SHOP;
                $order->customer_type = 'Retail';
                $order->exchange_rate_fee = $this->storeManager->getExchangeRate();
                $order->total_paid_amount_local = 0;

                $order->total_promotion_amount_local = $orderTotalDiscount;

                $order->total_intl_shipping_fee_local = $orderPayment->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee')[1];

                // 2 .seller
                $seller = $orderPayment->seller;
                $seller->portal = $orderPayment->portal;
                $seller = $seller->safeCreate();

                // 3. update seller for order
                $order->seller_id = $seller->id;
                $order->seller_name = $seller->seller_name;
                $order->seller_store = $seller->seller_link_store;

                if (!$order->save(false)) {
                    $transaction->rollBack();
                    return $this->response(false, 'can not create order');
                }

                foreach ($orderPayment->getAdditionalFees()->keys() as $key) {
                    $feeValue = $orderPayment->getAdditionalFees()->get($key);
                    $feeValue = reset($feeValue);

                    $fee = new TargetAdditionalFee($feeValue);
                    $fee->target = 'order';
                    $fee->target_id = $order->id;

                    if (!$fee->save(false)) {
                        $transaction->rollBack();
                        return $this->response(false, 'can not create order');
                    }
                }
                $updateOrderAttributes = [];

                // 4 products
                foreach ($orderPayment->products as $paymentProduct) {
                    $product = clone $paymentProduct;
                    unset($product['productFees']);
                    unset($product['category']);
                    $product->order_id = $order->id;
                    $product->quantity_purchase = null;
                    /** Todo */
                    $product->quantity_inspect = null;
                    /** Todo */
                    $product->variations = null;
                    /** Todo */
                    $product->variation_id = null;
                    $product->remove = 0;
                    $product->version = '4.0';

                    // 6. // step 4: create category for each item
                    $category = $paymentProduct->category;
                    $category = $category->safeCreate();

                    // 7. set category id for product
                    $product->category_id = $category->id;
                    // 8. set seller id for product
                    $product->seller_id = $seller->id;

                    // save total product discount here
                    if (!$product->save(false)) {
                        $transaction->rollBack();
                        return $this->response(false, 'can not save a product');
                    }

                    foreach ($paymentProduct->productFees as $feeName => $productFee) {
                        $fee = clone $productFee;
                        $fee->target = 'product';
                        $fee->target_id = $product->id;

                        if (!$fee->save(false)) {
                            $transaction->rollBack();
                            return $this->response(false, 'can not create order');
                        }
                    }

                }

                $updateOrderAttributes['ordercode'] = WeshopHelper::generateTag($order->id, 'WSVN', 16);
                $updateOrderAttributes['total_final_amount_local'] = $order->total_amount_local - $order->total_promotion_amount_local;
                $order->updateAttributes($updateOrderAttributes);
                $orders[$order->ordercode] = $order;
            }
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception, __METHOD__);
            return ['success' => false, 'message' => $exception->getMessage()];
        }

        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->customer_id = $this->user ? $this->user->getId() : null;
        $paymentTransaction->store_id = $payment->storeManager->getId();
        $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_PAYMENT;
        $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_CREATED;
        $paymentTransaction->transaction_code = $payment->transaction_code;
        $paymentTransaction->transaction_customer_name = $payment->customer_name;
        $paymentTransaction->transaction_customer_email = $payment->customer_email;
        $paymentTransaction->transaction_customer_phone = $payment->customer_phone;
        $paymentTransaction->transaction_customer_address = $payment->customer_address;
        $paymentTransaction->transaction_customer_postcode = $payment->customer_postcode;
        $paymentTransaction->transaction_customer_address = $payment->customer_address;
        $paymentTransaction->transaction_customer_district = $payment->customer_district;
        $paymentTransaction->transaction_customer_city = $payment->customer_city;
        $paymentTransaction->transaction_customer_country = $payment->customer_country;
        $paymentTransaction->payment_provider = $payment->payment_provider_name;
        $paymentTransaction->payment_method = $payment->payment_method_name;
        $paymentTransaction->payment_bank_code = $payment->payment_bank_code;
        $paymentTransaction->coupon_code = $payment->coupon_code;
        $paymentTransaction->used_xu = $payment->use_xu;
        $paymentTransaction->bulk_point = $payment->bulk_point;
        $paymentTransaction->total_discount_amount = $payment->total_discount_amount;
        $paymentTransaction->before_discount_amount_local = $payment->total_order_amount;
        $paymentTransaction->transaction_amount_local = $payment->getTotalAmountDisplay();
        $paymentTransaction->payment_type = $payment->type;
        $paymentTransaction->save(false);
        foreach ($orders as $order) {
            /** @var  $order Order */
            $childTransaction = clone $paymentTransaction;
            $childTransaction->id = null;
            $childTransaction->isNewRecord = true;
            $childTransaction->transaction_amount_local = $order->total_final_amount_local;
            $childTransaction->total_discount_amount = 0;
            $childTransaction->parent_transaction_code = $paymentTransaction->transaction_code;
            $childTransaction->transaction_code = PaymentService::generateTransactionCode('ORDER');
            $childTransaction->order_code = $order->ordercode;
            $childTransaction->save(false);
        }
        $res = $payment->processPayment();
        if ($res->success === false) {
            return $this->response(false, $res->message);
        }
        $paymentTransaction->third_party_transaction_code = $res->token;
        $paymentTransaction->third_party_transaction_status = $res->status;
        $paymentTransaction->third_party_transaction_link = $res->checkoutUrl;
        $paymentTransaction->save(false);
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("action time : $time", __METHOD__);
        return $this->response(true, 'create success', $res);
    }

    public function actionReturn($merchant)
    {
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);
        $cartUrl = Url::toRoute('/checkout/cart');
        $redirectUrl = Url::toRoute('/account/order', true);

        // @Phuc ToDo Check log Respone NL
        Yii::info(" res Object response :");
        Yii::info($res, __METHOD__);

        if ($res->success === false) {
            Yii::info(" res return false redirect url :" . $this->redirect($cartUrl));
            return $this->redirect($cartUrl);
        }
        if ($res->checkoutUrl !== null) {
            $redirectUrl = $res->checkoutUrl;
        }
        /** @var $paymentTransaction PaymentTransaction */
        if (($paymentTransaction = $res->paymentTransaction) instanceof PaymentTransaction && $paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS) {
            foreach ($paymentTransaction->childPaymentTransaction as $child) {
                if (($order = $child->order) !== null) {
                    $order->total_paid_amount_local = $child->transaction_amount_local;
                    $order->save(false);
                }
            }
            return $this->redirect($redirectUrl);

        }
        return $this->redirect($cartUrl);

    }


    public function actionCheckRecursive($merchant)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (($token = Yii::$app->request->get('token')) === null) {
            return false;
        }
        if ($merchant === 'nganluong32') {
            $client = new NganLuongClient();
            $res = $client->GetTransactionDetail($token);
            return (string)$res['error_code'] === '00';
        }
        return false;
    }

    public function actionFailed()
    {

    }
}
