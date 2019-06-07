<?php


namespace frontend\modules\payment\controllers;

use common\components\cart\CartHelper;
use common\components\cart\CartSelection;
use common\helpers\WeshopHelper;
use common\models\Address;
use common\models\Category;
use common\models\Order;
use common\models\PaymentTransaction;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use common\models\SystemDistrict;
use common\models\SystemStateProvince;
use frontend\modules\payment\models\OtpVerifyForm;
use frontend\modules\payment\PaymentContextView;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\wallet\WalletHideProvider;
use frontend\modules\payment\providers\wallet\WalletService;
use common\products\BaseProduct;
use common\promotion\PromotionResponse;
use frontend\modules\payment\models\ShippingForm;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\Response;
use frontend\modules\payment\Payment;

class PaymentController extends BasePaymentController
{

    public function actionProcess()
    {
        $start = microtime(true);
        $bodyParams = $this->request->bodyParams;
        if (($customer = $this->user) === null) {
            return $this->response(false, 'not login');
        }
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
//        $shippingForm->setDefaultValues(); // remove it get from POST pls
//        $shippingForm->ensureReceiver();
        if ($shippingForm->save_my_address) {
            $my_address = new Address();
            $my_address->first_name = $shippingForm->buyer_name;
            $my_address->phone = $shippingForm->buyer_phone;
            $my_address->email = $shippingForm->buyer_email;
            $my_address->country_id = 1;
            $my_address->country_name = 'Viet Nam';
            $my_address->province_id = $shippingForm->buyer_province_id;
            $my_address->province_name = ($province = SystemStateProvince::findOne($shippingForm->buyer_province_id)) ? $province->name : '';
            $my_address->district_id = $shippingForm->buyer_district_id;
            $my_address->district_name = ($district = SystemDistrict::findOne($shippingForm->buyer_district_id)) ? $district->name : '';
            $my_address->address = $shippingForm->buyer_address;
            $my_address->customer_id = $this->user->getId();
            $my_address->type = Address::TYPE_PRIMARY;
            $my_address->is_default = Address::find()->where(['customer_id' => $this->user->getId(), 'type' => Address::TYPE_PRIMARY, 'is_default' => 1])->count() ? 0 : 1;
            $my_address->save(false);
            $shippingForm->receiver_address_id = $my_address->id;
            if (!$shippingForm->other_receiver) {
                $my_shiping = new Address();
                $addressShipping = $my_address->getAttributes();
                unset($addressShipping['id']);
                $my_shiping->setAttributes($addressShipping);
                $my_shiping->type = Address::TYPE_SHIPPING;
                $my_shiping->is_default = Address::find()->where(['customer_id' => $this->user->getId(), 'type' => Address::TYPE_SHIPPING, 'is_default' => 1])->count() ? 0 : 1;
                $my_shiping->save(false);
                $shippingForm->receiver_address_id = $my_shiping->id;
            } else {
                $my_shiping = new Address();
                $my_shiping->first_name = $shippingForm->receiver_name;
                $my_shiping->phone = $shippingForm->receiver_phone;
                $my_shiping->email = $shippingForm->receiver_email;
                $my_shiping->country_id = 1;
                $my_shiping->country_name = 'Viet Nam';
                $my_shiping->province_id = $shippingForm->receiver_province_id;
                $my_shiping->province_name = ($province = SystemStateProvince::findOne($shippingForm->receiver_province_id)) ? $province->name : '';
                $my_shiping->district_id = $shippingForm->receiver_district_id;
                $my_shiping->district_name = ($district = SystemDistrict::findOne($shippingForm->receiver_district_id)) ? $district->name : '';
                $my_shiping->address = $shippingForm->receiver_address;
                $my_shiping->customer_id = $this->user->getId();
                $my_shiping->type = Address::TYPE_SHIPPING;
                $my_shiping->is_default = Address::find()->where(['customer_id' => $this->user->getId(), 'type' => Address::TYPE_SHIPPING, 'is_default' => 1])->count() ? 0 : 1;
                $my_shiping->save(false);
                $shippingForm->receiver_address_id = $my_shiping->id;
            }
        }
        $address = isset($my_address) && $my_address instanceof Address ? $my_address : $this->user->primaryAddress;
        $payment->customer_name = implode(' ', [$address->first_name, $address->last_name]);
        $payment->customer_email = $address->email;
        $payment->customer_phone = $address->phone;
        $payment->customer_address = $address->address;
        $payment->customer_city = $address->province_name;
        $payment->customer_postcode = $address->post_code;
        $payment->customer_district = $address->district_name;
        $payment->customer_country = $address->country_name;
        $payment->createTransactionCode();
        /* @var $results PromotionResponse */
        $payment->checkPromotion();
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->customer_id = $this->user->getId();
        $paymentTransaction->store_id = $payment->storeManager->getId();
        $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_PAYMENT;
        $paymentTransaction->carts = implode(',', $payment->carts);
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
        $paymentTransaction->before_discount_amount_local = $payment->total_amount;
        $paymentTransaction->transaction_amount_local = $payment->total_amount - $payment->total_discount_amount;
        $paymentTransaction->payment_type = $payment->payment_type;
        $paymentTransaction->shipping = isset($my_shiping) && $my_shiping instanceof Address ? $my_shiping->id : $this->user->defaultShippingAddress->id;
        $paymentTransaction->save(false);
//        if ($payment->payment_provider === 42 || $payment->payment_provider === 45) {
//            $wallet = new WalletService([
//                'transaction_code' => $payment->transaction_code,
//                'total_amount' => $payment->total_amount - $payment->total_discount_amount,
//                'payment_provider' => $payment->payment_provider_name,
//                'payment_method' => $payment->payment_method_name,
//                'bank_code' => $payment->payment_bank_code,
//            ]);
//            $results = $wallet->topUpTransaction();
//            if ($results['success'] === true && isset($results['data']) && isset($results['data']['data']['code'])) {
//                $topUpCode = $results['data']['data']['code'];
//                $paymentTransaction->updateAttributes([
//                    'topup_transaction_code' => $topUpCode
//                ]);
//                $payment->transaction_code = $topUpCode;
//            }
//        }
        $res = $payment->processPayment();
        if ($res['success'] === false) {
            return $this->response(false, $res['message']);
        }
        $paymentTransaction->third_party_transaction_code = $res['data']['token'];
        $paymentTransaction->third_party_transaction_status = $res['data']['code'];
        $paymentTransaction->third_party_transaction_link = $res['data']['checkoutUrl'];
        $paymentTransaction->save(false);
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("action time : $time", __METHOD__);
        return $this->response(true, 'create success', $res['data']);
    }

    public function actionReturn($merchant)
    {
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);
        $cartUrl = Url::toRoute('/checkout/cart');
        $redirectUrl = Url::toRoute('/account/order', true);
        if (!isset($res) || $res['success'] === false || !isset($res['data'])) {
            return $this->redirect($cartUrl);
        }

        $data = $res['data'];

        if (isset($data['redirectUrl'])) {
            $redirectUrl = $data['redirectUrl'];
        }
        /** @var $paymentTransaction PaymentTransaction */
        if (($paymentTransaction = $data['transaction']) instanceof PaymentTransaction) {
            $payment = new Payment([
                'carts' => StringHelper::explode($paymentTransaction->carts, ','),
                'transaction_code' => $paymentTransaction->transaction_code,
                'customer_name' => $paymentTransaction->transaction_customer_name,
                'customer_email' => $paymentTransaction->transaction_customer_email,
                'customer_phone' => $paymentTransaction->transaction_customer_phone,
                'customer_address' => $paymentTransaction->transaction_customer_address,
                'customer_postcode' => $paymentTransaction->transaction_customer_postcode,
                'customer_district' => $paymentTransaction->transaction_customer_district,
                'customer_city' => $paymentTransaction->transaction_customer_city,
                'customer_country' => $paymentTransaction->transaction_customer_country,
                'payment_provider_name' => $paymentTransaction->payment_provider,
                'payment_method_name' => $paymentTransaction->payment_method,
                'payment_bank_code' => $paymentTransaction->payment_bank_code,
                'coupon_code' => $paymentTransaction->coupon_code,
                'use_xu' => $paymentTransaction->used_xu,
                'bulk_point' => $paymentTransaction->bulk_point,
                'total_discount_amount' => $paymentTransaction->total_discount_amount,
                'total_amount' => $paymentTransaction->before_discount_amount_local,
                'payment_type' => $paymentTransaction->payment_type,
                'isPaid' => $paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS,
            ]);

            $receiverAddress = Address::findOne($paymentTransaction->shipping);
            if ($paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS) {
                $createResponse = $payment->createOrder($receiverAddress);
                if ($createResponse['success'] && isset($createResponse['data']['orderCodes'])) {
                    Yii::info($createResponse['data']);
                    foreach ($createResponse['data']['orderCodes'] as $orderCode => $info) {
                        $coupon_codes = ArrayHelper::getValue($info, 'promotion');
                        if ($coupon_codes !== null && is_array($coupon_codes)) {
                            $coupon_codes = implode(',', $coupon_codes);
                        }
                        $childTransaction = clone $paymentTransaction;
                        $childTransaction->id = null;
                        $childTransaction->isNewRecord = true;
                        $childTransaction->coupon_code = $coupon_codes;
                        $childTransaction->transaction_amount_local = ArrayHelper::getValue($info, 'totalPaid', 0);
                        $childTransaction->total_discount_amount = ArrayHelper::getValue($info, 'discountAmount', 0);
                        $childTransaction->parent_transaction_code = $paymentTransaction->transaction_code;
                        $childTransaction->transaction_code = PaymentService::generateTransactionCode('ORDER');
                        $childTransaction->order_code = $orderCode;
                        $childTransaction->save(false);
                    }
                    foreach ($payment->carts as $key) {
                        $this->cartManager->removeItem($payment->payment_type, $key);
                    }

                }
            }
            return $this->redirect($redirectUrl);

        }
        return $this->redirect($cartUrl);

    }

    public function actionFailed()
    {

    }
}