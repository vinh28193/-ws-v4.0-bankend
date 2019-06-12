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
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();

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
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->customer_id = $this->user ? $this->user->getId() : null;
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
        $paymentTransaction->before_discount_amount_local = $payment->total_order_amount;
        $paymentTransaction->transaction_amount_local = $payment->getTotalAmountDisplay();
        $paymentTransaction->payment_type = $payment->type;
        $paymentTransaction->save(false);

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
        if ($res->success === false) {
            return $this->redirect($cartUrl);
        }
        if ($res->checkoutUrl !== null) {
            $redirectUrl = $res->checkoutUrl;
        }
        /** @var $paymentTransaction PaymentTransaction */
        if (($paymentTransaction = $res->paymentTransaction) instanceof PaymentTransaction) {
            $payment = new Payment([
                'carts' => StringHelper::explode($paymentTransaction->carts, ','),
                'uuid' => $this->filterUuid(),
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
                'total_order_amount' => $paymentTransaction->before_discount_amount_local,
                'type' => $paymentTransaction->payment_type,
                'isPaid' => $paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS,
            ]);
            $createResponse = $payment->createOrder();
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
                    $this->cartManager->removeItem($payment->type, $key);
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