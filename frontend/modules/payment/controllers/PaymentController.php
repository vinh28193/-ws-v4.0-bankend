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

    const TYPE_TRANSACTION_PAY_ORDER = 'PAY_ORDER';
    const TYPE_TRANSACTION_WITHDRAW = 'WITH_DRAW';
    const TYPE_TRANSACTION_TOP_UP = 'TOP_UP';
    const TYPE_TRANSACTION_REFUND = 'REFUND';

    const STATUS_QUEUE = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_CANCEL = 3;
    const STATUS_FAIL = 4;

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
        if($shippingForm->save_my_address){
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
            $my_address->is_default = Address::find()->where(['customer_id' => $this->user->getId(), 'type' => Address::TYPE_PRIMARY , 'is_default' => 1 ])->count() ? 0 : 1;
            $my_address->save(false);
            $shippingForm->receiver_address_id = $my_address->id;
            if(!$shippingForm->other_receiver){
                $my_shiping = new Address();
                $addressShipping = $my_address->getAttributes();
                unset($addressShipping['id']);
                $my_shiping->setAttributes($addressShipping);
                $my_shiping->type = Address::TYPE_SHIPPING;
                $my_shiping->is_default = Address::find()->where(['customer_id' => $this->user->getId(), 'type' => Address::TYPE_SHIPPING , 'is_default' => 1 ])->count() ? 0 : 1;
                $my_shiping->save(false);
                $shippingForm->receiver_address_id = $my_shiping->id;
            }
        }
        $payment->customer_name = $shippingForm->buyer_name;
        $payment->customer_email = $shippingForm->buyer_email;
        $payment->customer_phone = $shippingForm->buyer_phone;
        $payment->customer_address = $shippingForm->buyer_address;
        $payment->customer_city = $shippingForm->buyer_province_id;
        $payment->customer_postcode = $shippingForm->buyer_post_code;
        $payment->customer_district = $shippingForm->buyer_district_id;
        $payment->customer_country = $shippingForm->buyer_country_id;
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
        $paymentTransaction->shipping = $shippingForm->receiver_address_id;
        $paymentTransaction->save(false);
        if ($payment->payment_provider === 42) {
            $wallet = new WalletService([
                'transaction_code' => $payment->transaction_code,
                'total_amount' => $payment->total_amount - $payment->total_discount_amount,
                'payment_provider' => $payment->payment_provider_name,
                'payment_method' => $payment->payment_method_name,
                'bank_code' => $payment->payment_bank_code,
            ]);
            $results = $wallet->topUpTransaction();
            if ($results['success'] === true && isset($results['data']) && isset($results['data']['data']['code'])) {
                $topUpCode = $results['data']['data']['code'];
                $paymentTransaction->updateAttributes([
                    'topup_transaction_code' => $topUpCode
                ]);
                $payment->transaction_code = $topUpCode;
            }
        }
        $res = $payment->processPayment();
        if ($res['success'] === false) {
            return $this->response(false, $res['message']);
        }
        $paymentTransaction->third_party_transaction_code = $res['data']['token'];
        $paymentTransaction->third_party_transaction_status = $res['data']['code'];
        $paymentTransaction->third_party_transaction_link = $res['data']['checkoutUrl'];
        $paymentTransaction->save(false);
        $time = $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("action time : $time", __METHOD__);
        return $this->response(true, 'create success', $res['data']);
    }

    public function actionReturn($merchant)
    {
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);
        if (!isset($res) || $res['success'] === false || !isset($res['data'])) {
            return $this->redirect('failed');
        }

        $data = $res['data'];
        /** @var $paymentTransaction PaymentTransaction */
        if (($paymentTransaction = $data['transaction']) instanceof PaymentTransaction && ($paymentTransaction->payment_method === 'TTVP' || $paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS)) {
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

            ]);
            $redirectUrl = Yii::$app->homeUrl;
            if (isset($data['redirectUrl'])) {
                $redirectUrl = $data['redirectUrl'];
            }
            $receiverAddress = Address::findOne($paymentTransaction->shipping);
            /* @var $results PromotionResponse */
            $createResponse = $payment->createOrder($receiverAddress);
            if ($createResponse['success']) {
                return $this->redirect($redirectUrl);
            }

        }
        return $this->redirect(Url::toRoute('/checkout/cart'));

    }

    public function actionOtpVerify($code)
    {
        $request = $this->request;
        $redirectUri = $this->request->referrer;
        $otpVerifyForm = new OtpVerifyForm(['transactionCode' => $code]);
        $walletService = new WalletService(['transaction_code' => $code]);
        $transaction = $walletService->transactionDetail();
        $data = [];
        $msg = [];
        $statusOtp = false;
        if ($transaction['success']) {
            $data = $transaction['data'];
        }

        /**
         * Thông tin transaction
         *  - 'wallet_transaction_code' : transaction Code
         *   -'type' : kiểu tracsation ('PAY_ORDER ...)
         *   -'description',
         *   -'totalAmount',
         *   -'request_content',
         *   -'payment_method',
         *   -'payment_provider_name',
         *   -'payment_bank_code',
         *   -'payment_transaction',
         *   -'order_number',
         *   -'status'
         */
        $transactionDetail = isset($data['transactionInfo']) ? $data['transactionInfo'] : [];
        /**
         * Thông tin của otp
         * Trường hợp transaction không có đóng bắng (isValid = false, hoặc (transaction.status = failse))
         * verified
         * receive_type
         * receive_type_text
         * send_to
         * count
         * expired_at
         * expired_timestamp
         * refresh_count
         * refresh_expired_timestamp
         * refresh_expired_at
         *
         */

        $otpInfo = isset($data['otpInfo']) ? $data['otpInfo'] : [];
        /**
         * Thông tin user dùng ví (cách giử otp)
         * - user name,
         * - phone
         * - email
         */
        $walletInterview = isset($data['walletInterview']) ? $data['walletInterview'] : [];
        $isValid = isset($data['isValid']) ? $data['isValid'] : false;
        if (count($transactionDetail) > 0) {
            $binCode = $transactionDetail['order_number'];
            if ($transactionDetail['type'] === self::TYPE_TRANSACTION_WITHDRAW) {
                $redirectUri = Url::to("/account/wallet/" . $code . "/detail.html", true);
            } else {
                $redirectUri = Url::to("/account/order", true);
            }

            if ($transactionDetail['status'] === self::STATUS_CANCEL || $transactionDetail['status'] === self::STATUS_COMPLETE || $transactionDetail['status'] === self::STATUS_PROCESSING) {
                return $this->redirect($redirectUri);
            } elseif ($transactionDetail['status'] === self::STATUS_FAIL) {
                $statusOtp = false;
            }
        }

        if (count($otpInfo) > 0) {

            if ($otpInfo['verified']) {
                if ($transactionDetail['status'] === self::STATUS_PROCESSING && $transactionDetail['type'] === self::TYPE_TRANSACTION_WITHDRAW) {
                    $msg[] = 'Xác thực OTP hoàn tất. Vui long chờ đợi hệ thống sử lý. Xin cảm ơn.';
                } else {
                    return $this->redirect($redirectUri);
                }

            }
            $otpVerifyForm->otpReceive = $otpInfo['receive_type'];
            $msg[] = 'Mã xác thực otp đã gửi tới ' . ' ' . $otpInfo['receive_type_text'] . ': ' . $otpInfo['send_to'];
            $msg[] = 'OTP có hiệu lực trong:' . Html::tag('span', $otpInfo['expired_at'], ['data-time-expired' => $otpInfo['expired_timestamp'], 'data-redirect-uri' => $redirectUri, 'class' => 'otp-expired-cooldown text-red']);
        }
        $msg = count($msg) > 0 ? implode('. ', $msg) : null;
        return Yii::$app->getView()->renderAjax('otp-verify', [
            'statusOtp' => $isValid,
            'isValid' => $isValid,
            'msg' => $msg,
            'otpVerifyForm' => $otpVerifyForm,
            'transactionDetail' => $transactionDetail,
            'walletInterview' => $walletInterview,
            'redirectUri' => $redirectUri,
        ], new PaymentContextView());
    }

    public function actionFailed()
    {

    }
}