<?php


namespace frontend\modules\payment\controllers;


use common\components\gaSetting;
use common\modelsMongo\ChatMongoWs;
use frontend\modules\payment\models\Order;
use common\components\employee\Employee;
use common\helpers\WeshopHelper;
use common\helpers\UtilityHelper;
use common\models\Address;
use common\models\db\TargetAdditionalFee;
use common\models\PaymentTransaction;
use common\promotion\PromotionResponse;
use frontend\modules\payment\models\ShippingForm;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\nganluong\ver3_2\NganLuongClient;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class PaymentController extends BasePaymentController
{

    public function actionProcess()
    {
        $start = microtime(true);
        $now = Yii::$app->getFormatter()->asDatetime('now');
        $bodyParams = $this->request->bodyParams;
        $paymentParams = $bodyParams['payment'];
        $shipping = ArrayHelper::getValue($bodyParams, 'shipping', []);

        if (($orderParams = ArrayHelper::remove($paymentParams, 'orders')) === null) {
            return $this->response(false, "can not get form null orders");
        }

        $payment = new Payment($paymentParams);

        if ($payment->page === Payment::PAGE_CHECKOUT && empty($shipping)) {
            return $this->response(false, Yii::t('frontend', 'Can not create payment from unknown information'));
        }

        if ($payment->page === Payment::PAGE_ADDITION && ($payment->transaction_code === null || $payment->transaction_code === '')) {
            return $this->response(false, Yii::t('frontend', 'Can not create payment invalid transaction code'));
        }

        $shippingForm = new ShippingForm();
        $shippingForm->load($shipping,'');

        if(!$shippingForm->validate()){
            return $this->response(false, $shippingForm->getFirstErrors());
        }
        $shippingForm->ensureReceiver();

        foreach ($orderParams as $orderParam) {
            $totalAmountLocal = ArrayHelper::remove($orderParam, 'totalAmountLocal', 0);
            if (isset($orderParam['totalFinalAmount'])) {
                unset($orderParam['totalFinalAmount']);
            }
            $orderPayment = new Order($orderParam);
            if ($orderPayment->cartId !== null && $orderPayment->createOrderFromCart() !== false) {
                $orders[$orderPayment->cartId] = $orderPayment;
            } else if ($orderPayment->ordercode !== null && ($order = Order::findOne(['ordercode' => $orderPayment->ordercode])) !== null) {
                $order->getAdditionalFees()->removeAll();
                $order->getAdditionalFees()->fromArray($orderPayment->getAdditionalFees()->toArray());
                if ($payment->page === Payment::PAGE_ADDITION && $totalAmountLocal > 0) {
                    $order->setTotalAmount($totalAmountLocal);
                    $order->getAdditionalFees()->removeAll();
                }
                $shippingForm->loadAddressFormOrder($order);
                $orders[$order->ordercode] = $order;
            }
        }

        $payment->setOrders($orders);

        if (count($payment->errors) > 0) {
            return $this->response(false, implode(', ', $payment->errors));
        }
        $shippingParams = [
            'buyer_name' => $shippingForm->buyer_name,
            'buyer_address' => $shippingForm->buyer_address,
            'buyer_email' => $shippingForm->buyer_email,
            'buyer_phone' => $shippingForm->buyer_phone,
            'buyer_country_id' => $this->storeManager->store->country_id,
            'buyer_province_id' => $shippingForm->buyer_province_id,
            'buyer_district_id' => $shippingForm->buyer_district_id,
            'buyer_post_code' => $shippingForm->buyer_post_code,
            'buyer_province_name' => $shippingForm->getBuyerProvinceName(),
            'buyer_district_name' => $shippingForm->getBuyerDistrictName(),
            'buyer_country_name' => $this->storeManager->store->country_name,
            'receiver_name' => $shippingForm->receiver_name,
            'receiver_address' => $shippingForm->receiver_address,
            'receiver_phone' => $shippingForm->receiver_phone,
            'receiver_country_id' => $this->storeManager->store->country_id,
            'receiver_province_id' => $shippingForm->receiver_province_id,
            'receiver_district_id' => $shippingForm->receiver_district_id,
            'receiver_post_code' => $shippingForm->receiver_post_code,
            'receiver_province_name' => $shippingForm->getReceiverProvinceName(),
            'receiver_district_name' => $shippingForm->getReceiverDistrictName(),
            'receiver_country_name' => $this->storeManager->store->country_name,
            'receiver_address_id' => null,
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
                $shippingParams['receiver_name'] = $buyer->first_name;
                $shippingParams['receiver_address'] = $buyer->address;
                $shippingParams['receiver_phone'] = $buyer->phone;
                $shippingParams['receiver_province_id'] = $buyer->province_id;
                $shippingParams['receiver_district_id'] = $buyer->district_id;
                $shippingParams['receiver_province_name'] = $buyer->province_name;
                $shippingParams['receiver_district_name'] = $buyer->district_name;
                $shippingParams['receiver_post_code'] = $buyer->post_code;
            }

        }
        if ($shippingForm->other_receiver !== 'false') {
            if ((int)$shippingForm->enable_receiver === ShippingForm::NO) {
                $shippingParams['receiver_name'] = $shippingForm->receiver_name;
                $shippingParams['receiver_address'] = $shippingForm->receiver_address;
                $shippingParams['receiver_phone'] = $shippingForm->receiver_phone;
                $shippingParams['receiver_province_id'] = $shippingForm->receiver_province_id;
                $shippingParams['receiver_province_name'] = $shippingForm->getReceiverProvinceName();
                $shippingParams['receiver_district_id'] = $shippingForm->receiver_district_id;
                $shippingParams['receiver_district_name'] = $shippingForm->getReceiverDistrictName();
                $shippingParams['receiver_post_code'] = $shippingForm->receiver_post_code;
                $shippingParams['receiver_country_id'] = $this->storeManager->store->country_id;
                $shippingParams['receiver_country_name'] = $this->storeManager->store->country_name;
            } else if ($shippingForm->receiver_address_id !== null && ($receiver = Address::findOne($shippingForm->receiver_address_id)) !== null) {
                $shippingParams['receiver_name'] = $receiver->address;
                $shippingParams['receiver_address'] = $receiver->phone;
                $shippingParams['receiver_phone'] = $receiver->province_id;
                $shippingParams['receiver_province_id'] = $receiver->district_id;
                $shippingParams['receiver_district_id'] = $receiver->post_code;
                $shippingParams['receiver_province_name'] = $receiver->province_name;
                $shippingParams['receiver_district_name'] = $receiver->district_name;
                $shippingParams['receiver_post_code'] = $receiver->post_code;
            }
        }
        $payment->customer_name = $shippingForm->buyer_name;
        $payment->customer_email = $shippingForm->buyer_email;
        $payment->customer_phone = $shippingForm->buyer_phone;
        $payment->customer_address = $shippingForm->buyer_address;
        $payment->customer_city = $shippingForm->getBuyerProvinceName();
        $payment->customer_postcode = $shippingForm->buyer_post_code;
        $payment->customer_district = $shippingForm->getBuyerDistrictName();
        $payment->customer_country = $this->storeManager->store->country_name;


        if ($payment->page !== Payment::PAGE_ADDITION) {
            $paymentTransaction = new PaymentTransaction();

            $parentTransaction = null;
            if ($payment->transaction_code !== null && ($parentTransaction = PaymentService::findParentTransaction($payment->transaction_code)) !== null) {
                $paymentTransaction = clone $parentTransaction;
                $paymentTransaction->isNewRecord = true;
                $paymentTransaction->id = null;
            } elseif ($payment->page === Payment::PAGE_BILLING && $parentTransaction === null) {
                /** @var  $order  Order */
                $order = array_values($payment->getOrders())[0];
                if ($order !== null && ($childPaymentTransaction = PaymentService::findChildTransaction($payment->transaction_code, $order->ordercode)) !== null) {
                    $paymentTransaction = clone $childPaymentTransaction;
                    $paymentTransaction->isNewRecord = true;
                    $paymentTransaction->id = null;
                }

            }

            $payment->createTransactionCode();

            $paymentTransaction->customer_id = $this->user ? $this->user->getId() : null;
            $paymentTransaction->store_id = $payment->storeManager->getId();
            $paymentTransaction->carts = implode(',', array_keys($payment->getOrders()));
            $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_PAYMENT;
            $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_CREATED;
            $paymentTransaction->transaction_code = $payment->transaction_code;
            $paymentTransaction->parent_transaction_code = $parentTransaction instanceof PaymentTransaction ? $parentTransaction->transaction_code : null;
            $paymentTransaction->transaction_customer_name = $payment->customer_name;
            $paymentTransaction->transaction_customer_email = $payment->customer_email;
            $paymentTransaction->transaction_customer_phone = $payment->customer_phone;
            $paymentTransaction->transaction_customer_address = $payment->customer_address;
            $paymentTransaction->transaction_customer_postcode = $payment->customer_postcode;
            $paymentTransaction->transaction_customer_address = $payment->customer_address;
            $paymentTransaction->transaction_customer_district = $payment->customer_district;
            $paymentTransaction->transaction_customer_city = $payment->customer_city;
            $paymentTransaction->transaction_customer_country = $payment->customer_country;
            $paymentTransaction->payment_provider = $payment->payment_provider;
            $paymentTransaction->payment_method = $payment->payment_method;
            $paymentTransaction->payment_bank_code = $payment->payment_bank_code;
            $paymentTransaction->total_discount_amount = 0;
            $paymentTransaction->before_discount_amount_local = $payment->getTotalAmountDisplay();
            $paymentTransaction->transaction_amount_local = $payment->getTotalAmountDisplay();
            $paymentTransaction->payment_type = $payment->type;
            $paymentTransaction->save(false);
            /* @var $results PromotionResponse */
        }
        if ($payment->transaction_code !== null) {
            $payment->return_url = PaymentService::createReturnUrl($payment->payment_provider);
            $payment->cancel_url = PaymentService::createCheckoutUrl(null, $payment->transaction_code);
        }
        $payment->getPaymentMethodProviderName();
        $payment->checkPromotion();
        if ($payment->page === Payment::PAGE_CHECKOUT) {
            $transaction = Order::getDb()->beginTransaction();
            $orders = [];
            try {
                foreach ($payment->getOrders() as $key => $orderPayment) {
                    $order = clone $orderPayment;
                    $order->setAttributes($shippingParams);
                    if (!empty($orderPayment->courierDetail)) {
                        $order->courier_name = implode(' ', [$orderPayment->courierDetail['courier_name'], $orderPayment->courierDetail['service_name']]);
                        $order->courier_service = $orderPayment->courierDetail['service_code'];
                        $order->courier_delivery_time = implode(' ', [$orderPayment->courierDetail['min_delivery_time'], $orderPayment->courierDetail['max_delivery_time']]);
                    }
                    $orderTotalDiscount = 0;
                    unset($order['products']);
                    unset($order['seller']);
                    unset($order['saleSupport']);
                    // 1 order
                    $order->type_order = Order::TYPE_SHOP;
                    $order->payment_type = $payment->payment_type;
                    $order->customer_type = $orderPayment->getUserLevel();
                    $order->exchange_rate_fee = $this->storeManager->getExchangeRate();
                    $order->total_paid_amount_local = 0;

                    $order->total_promotion_amount_local = $order->discountAmount;

                    $order->total_intl_shipping_fee_local = $orderPayment->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee')[1];

                    $order->total_fee_amount_local = $orderPayment->getAdditionalFees()->getTotalAdditionalFees()[1];

                    $order->payment_provider = $payment->payment_provider_name;
                    $order->payment_method = $payment->payment_method_name;
                    $order->payment_bank = $payment->payment_bank_code;

                    $order->total_final_amount_local = $orderPayment->getTotalFinalAmount();

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
                        return $this->response(false, Yii::t('yii', 'An internal server error occurred.'));
                    }

                    foreach ($orderPayment->getAdditionalFees()->toArray() as $feeName => $arrayFee) {
                        // luu tong
                        $firstFee = $arrayFee[0];
                        $firstFee = new TargetAdditionalFee($firstFee);
                        list($firstFee->amount, $firstFee->local_amount) = $orderPayment->getAdditionalFees()->getTotalAdditionalFees($feeName);
                        $firstFee->target = 'order';
                        $firstFee->target_id = $order->id;
                        if (!$firstFee->save(false)) {
                            $transaction->rollBack();
                            return $this->response(false, Yii::t('yii', 'An internal server error occurred.'));
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

                        $product->product_name = UtilityHelper::removeEmoji($product->product_name);

                        // save total product discount here
                        if (!$product->save(false)) {
                            $transaction->rollBack();
                            return $this->response(false, Yii::t('yii', 'An internal server error occurred.'));
                        }

                        foreach ($paymentProduct->productFees as $feeName => $productFee) {
                            $fee = clone $productFee;
                            $fee->target = 'product';
                            $fee->target_id = $product->id;

                            if (!$fee->save(false)) {
                                $transaction->rollBack();
                                return $this->response(false, Yii::t('yii', 'An internal server error occurred.'));
                            }
                        }
                    }
                    $updateOrderAttributes['ordercode'] = WeshopHelper::generateBinCode($order->id, 6);
                    $updateOrderAttributes['total_final_amount_local'] = $orderPayment->getTotalFinalAmount() - $order->total_promotion_amount_local;
                    $order->updateAttributes($updateOrderAttributes);
                    $orders[$order->ordercode] = $order;
                }
                $transaction->commit();
                $payment->setOrders($orders);
            } catch (Exception $exception) {
                $transaction->rollBack();
                Yii::error($exception, __METHOD__);
                return $this->response(false, Yii::t('yii', 'An internal server error occurred.'));
            }
        } elseif ($payment->page === Payment::PAGE_BILLING) {
            // check phi van chuyen quoc te;

        }

        // ToDo Push GA Checkout @Phuchc 15/7/2019

        $res = $payment->processPayment();
        if ($res->success === false) {
            return $this->response(false, $res->message);
        }

        if ($payment->page === Payment::PAGE_CHECKOUT) {
            $employee = new Employee();
            $assign = $employee->getAssign($payment->getOrders());
            if (!empty($assign) && isset($assign[0])) {
                $assign = array_shift($assign);
            }

            if (isset($paymentTransaction) && $paymentTransaction instanceof PaymentTransaction) {
                if (!empty($assign)) {
                    $paymentTransaction->support_id = $assign->id;
                }
                $fOrder = array_values($payment->getOrders())[0];
                $paymentTransaction->courier_delivery_time = $fOrder->courier_delivery_time;
                $paymentTransaction->courier_name = $fOrder->courier_name;
                $paymentTransaction->third_party_transaction_code = $res->token;
                $paymentTransaction->third_party_transaction_status = $res->status;
                $paymentTransaction->third_party_transaction_link = $res->checkoutUrl;
                $paymentTransaction->save(false);
                foreach ($payment->getOrders() as $key => $order) {
                    /** @var  $order Order */
                    $childTransaction = clone $paymentTransaction;
                    $childTransaction->id = null;
                    $childTransaction->isNewRecord = true;
                    $childTransaction->carts = $key;
                    $childTransaction->transaction_amount_local = $order->total_final_amount_local;
                    $childTransaction->total_discount_amount = $order->total_promotion_amount_local;
                    $childTransaction->before_discount_amount_local = $childTransaction->transaction_amount_local - $order->total_promotion_amount_local;
                    $childTransaction->order_code = $order->ordercode;
                    $childTransaction->courier_name = $order->courier_name;
                    $childTransaction->service_code = $order->courier_service;
                    $childTransaction->courier_delivery_time = $order->courier_delivery_time;
                    $childTransaction->save(false);

                    $order->payment_provider = $payment->payment_provider_name;
                    $order->payment_method = $payment->payment_method_name;
                    $order->payment_bank = $payment->payment_bank_code;

                    if ($shippingForm->receiver_address_id !== null) {
                        $order->receiver_address_id = $shippingForm->receiver_address_id;
                    }

                    $order->setAttributes($shippingParams);
                    $order->payment_transaction_code = $childTransaction->transaction_code;

                    if (!empty($assign)) {
                        $order->sale_support_id = $assign->id;
                        $order->support_email = $assign->email;
                    }
                    $order->save(false);
                    if ($order->cartId !== null) {
                        $order->removeCart();
                    }
                    try {

                        // ToDo @Phuchc gửi lên Mongodb nếu bị lỗi để gửi lại  9/7/2019

                        /** @var  $mailer yii\mail\BaseMailer */
                        $mailer = Yii::$app->mandrillMailer;
                        $mailer->viewPath = '@common/views/mail';
                        $mail = $mailer->compose(['html' => 'orderCreate-html'], [
                            'paymentTransaction' => $paymentTransaction,
                            'storeManager' => $this->storeManager
                        ]);
                        $from = [$this->storeManager->store->country_code === 'ID' ? 'no-reply@weshop.co.id' : 'no-reply@weshop.com.vn' => $this->storeManager->store->name];
                        $mail->setFrom($from);
                        $mail->setTo($payment->customer_email);
                        $mail->setSubject('Create Order Success');
                        $mail->send();

                    } catch (\Exception $exception) {
                        Yii::error($exception);
                    }

                }
            }

            /** @var  $fOrder Order */

        } elseif ($payment->page === Payment::PAGE_BILLING) {
            foreach ($payment->getOrders() as $key => $order) {
                $order->payment_provider = $payment->payment_provider_name;
                $order->payment_method = $payment->payment_method_name;
                $order->payment_bank = $payment->payment_bank_code;
                $order->payment_transaction_code = $payment->transaction_code;
                foreach ($shippingParams as $attr => $v) {
                    if (!WeshopHelper::compareValue($v, $order->$attr, WeshopHelper::isSubText($attr, '_id') ? 'integer' : 'string')) {
                        $order->$attr = $v;
                    }
                }
                $order->save(false);
            }
            if (isset($paymentTransaction) && $paymentTransaction instanceof PaymentTransaction && $paymentTransaction->order_code === null) {
                foreach ($payment->getOrders() as $oCode => $order) {
                    $childTransaction = clone $paymentTransaction;
                    $childTransaction->id = null;
                    $childTransaction->isNewRecord = true;
                    $childTransaction->carts = $oCode;
                    $childTransaction->transaction_amount_local = $order->total_final_amount_local;
                    $childTransaction->total_discount_amount = $order->total_promotion_amount_local;
                    $childTransaction->before_discount_amount_local = $childTransaction->transaction_amount_local - $order->total_promotion_amount_local;
                    $childTransaction->order_code = $order->ordercode;
                    $childTransaction->courier_name = $order->courier_name;
                    $childTransaction->service_code = $order->courier_service;
                    $childTransaction->courier_delivery_time = $order->courier_delivery_time;
                    $childTransaction->save(false);
                }

            }
        }
        gaSetting::gaPaymentProcess($payment);
        foreach ($payment->getOrders() as $key => $order) {
            ChatMongoWs::SendMessage('Create order: ' . $order->ordercode . '' .
                '<br>Amount: ' . $order->total_final_amount_local . '<br>Created At: ' . $order->created_at,
                $order->ordercode, ChatMongoWs::TYPE_GROUP_WS);
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("action time : $time", __METHOD__);
        return $this->response(true, 'create success', $res);

    }

    public
    function actionBilling()
    {
        $start = microtime(true);
        $now = Yii::$app->getFormatter()->asDatetime('now');
        $bodyParams = $this->request->bodyParams;
        $orders = ArrayHelper::remove($bodyParams, 'orders', []);
        if (empty($orders)) {
            return $this->response(false, Yii::t('frontend', 'Empty orders'));
        }
        $payment = new Payment($bodyParams);
        $payment->setOrders($orders);
        if ($payment->payment_provider === null && $payment->payment_method === null) {
            $payment->initDefaultMethod();
        } else {
            $payment->getPaymentMethodProviderName();
        }

        $paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $payment->transaction_code]);
        if ($paymentTransaction === null) {
            return $this->response(false, Yii::t('frontend', 'Not found transaction for invoice {code}', [
                'code' => $payment->transaction_code
            ]));
        }
        $payment->return_url = PaymentService::createReturnUrl($payment->payment_provider);
        $payment->cancel_url = PaymentService::createCancelUrl($paymentTransaction->transaction_code);
        if ($payment->type === 'order' && $paymentTransaction->order_code !== null) {
            $payment->cancel_url = PaymentService::createBillingUrl($paymentTransaction->order_code);
        }
        $res = $payment->processPayment();
        if ($res->success === false) {
            return $this->response(false, $res->message);
        }
        $paymentTransaction->payment_method = $payment->payment_method;
        $paymentTransaction->payment_provider = $payment->payment_provider;
        $paymentTransaction->payment_bank_code = $payment->payment_bank_code;
        $paymentTransaction->third_party_transaction_code = $res->token;
        $paymentTransaction->third_party_transaction_status = $res->status;
        $paymentTransaction->third_party_transaction_link = $res->checkoutUrl;
        foreach ($paymentTransaction->childPaymentTransaction as $childPaymentTransaction) {
            $childPaymentTransaction->payment_method = $payment->payment_method;
            $childPaymentTransaction->payment_provider = $payment->payment_provider;
            $childPaymentTransaction->payment_bank_code = $payment->payment_bank_code;
            $childPaymentTransaction->third_party_transaction_code = $res->token;
            $childPaymentTransaction->third_party_transaction_status = $res->status;
            $childPaymentTransaction->third_party_transaction_link = $res->checkoutUrl;
            $childPaymentTransaction->save(false);
        }
        $paymentTransaction->save(false);
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("action time : $time", __METHOD__);
        return $this->response(true, 'create success', $res);
    }

    public
    function actionReturn($merchant)
    {
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);

        /** @var $paymentTransaction PaymentTransaction */
        $paymentTransaction = $res->paymentTransaction;
        $redirectUrl = PaymentService::createSuccessUrl($paymentTransaction->transaction_code);
        $failUrl = PaymentService::createCancelUrl($paymentTransaction->transaction_code);

        if ($res->success === false) {
            return $this->redirect($failUrl);
        }

        if ($res->checkoutUrl !== null) {
            $redirectUrl = $res->checkoutUrl;
        }
        if ($paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS) {
            if ($paymentTransaction->transaction_type === PaymentTransaction::TRANSACTION_continue_payment) {
                $order = $paymentTransaction->order;
                $order->total_paid_amount_local += $paymentTransaction->transaction_amount_local;
                $order->save(false);
                ChatMongoWs::SendMessage('Payment success Order: ' . $order->ordercode .
                    '<br>Token: ' . $res->token .
                    '<br>Merchant: ' . $res->merchant
                    , $order->ordercode
                    , ChatMongoWs::TYPE_GROUP_WS);
            } else {
                foreach ($paymentTransaction->childPaymentTransaction as $child) {
                    $child->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                    $child->total_amount_success = $child->transaction_amount_local < $paymentTransaction->total_amount_success ? $child->transaction_amount_local : $paymentTransaction->total_amount_success;
                    if (($order = $child->order) !== null) {
                        $order->total_paid_amount_local = $child->transaction_amount_local;
                        if ($order->current_status == Order::STATUS_SUPPORTED) {
                            $order->current_status = Order::STATUS_READY2PURCHASE;
                        }
                        $order->save(false);
                        ChatMongoWs::SendMessage('Payment success Order: ' . $order->ordercode .
                            '<br>Token: ' . $res->token .
                            '<br>Merchant: ' . $res->merchant
                            , $order->ordercode
                            , ChatMongoWs::TYPE_GROUP_WS);
                    }
                    $child->save(false);
                }
            }


        }
        if ($merchant == 48) {
            return $this->redirect($redirectUrl, 200);
        } else {
            return $this->redirect($redirectUrl);
        }
    }


    public
    function actionCheckRecursive($merchant)
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

    public
    function actionFailed()
    {

    }

    public
    function actionReturnNicepay()
    {
        $merchant = 48;
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);

        /** @var $paymentTransaction PaymentTransaction */
        $paymentTransaction = $res->paymentTransaction;
        $redirectUrl = PaymentService::createSuccessUrl($paymentTransaction->transaction_code);
        $failUrl = PaymentService::createCancelUrl($paymentTransaction->transaction_code);

        if ($res->success === false) {
            return $this->redirect($failUrl);
        }

        if ($res->checkoutUrl !== null) {
            $redirectUrl = $res->checkoutUrl;
        }
        if ($paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS) {
            foreach ($paymentTransaction->childPaymentTransaction as $child) {
                $child->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;

                if (($order = $child->order) !== null) {
                    $order->total_paid_amount_local = $child->transaction_amount_local;
                    if ($order->current_status == Order::STATUS_SUPPORTED) {
                        $order->current_status = Order::STATUS_READY2PURCHASE;
                    }
                    $order->save(false);
                    ChatMongoWs::SendMessage('Payment success Order: ' . $order->ordercode .
                        '<br>Token: ' . $res->token .
                        '<br>Merchant: ' . $res->merchant
                        , $order->ordercode
                        , ChatMongoWs::TYPE_GROUP_WS);
                }
                $child->save(false);
            }

        }
        return $this->redirect($redirectUrl, 200);

    }
}
