<?php


namespace frontend\modules\payment\controllers;


use common\models\PaymentTransaction;
use frontend\modules\payment\models\OtpVerifyForm;
use frontend\modules\payment\PaymentContextView;
use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class WalletController extends BasePaymentController
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

    /**
     * @var WalletService
     */
    private $_walletService;

    /**
     * @return  WalletService
     */
    public function getWalletService()
    {
        if (!is_object($this->_walletService) && $this->getIsGuest() === false) {
            $this->_walletService = new WalletService();
        }
        return $this->_walletService;
    }

    /**
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->getWalletService()->isGuest();
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => 'testme',
                'foreColor' => 000000,
//                'height' => 60,
//                'minLength' => 1,
//                'maxLength' => 3,
//                'testLimit' => 5,
//                'padding' => 3,
//                'width' => 90
            ],
        ]);
    }

    public function actionCheckGuest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => WalletService::isGuest()];
    }

    public function actionOtpVerify($code)
    {
        $request = $this->request;
        $redirectUri = $request->referrer;
        $cancelUrl = Yii::$app->homeUrl;

        $otpVerifyForm = new OtpVerifyForm(['transactionCode' => $code]);
        $otpVerifyForm->transactionCode = $code;
        $data = $otpVerifyForm->detail();
        $msg = [];
        $statusOtp = false;

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
        if (count($transactionDetail) === 0 || ($transactionCode = $transactionDetail['order_number']) === null || ($paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $transactionCode])) === null) {
            return $this->redirect($redirectUri);
        }
        $otpVerifyForm->orderCode = $transactionDetail['order_number'];
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
        $otpVerifyForm->cancelUrl = $redirectUri;
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
            $msg[] = 'OTP có hiệu lực trong:' . Html::tag('span', $otpInfo['expired_at'], ['data-time-expired' => $otpInfo['expired_timestamp'], 'data-redirect-uri' => $redirectUri, 'class' => 'otp-expired-cooldown text-danger']);
        }
        $msg = count($msg) > 0 ? implode('. ', $msg) : null;
        return Yii::$app->getView()->renderAjax('otp-verify', [
            'statusOtp' => $isValid,
            'isValid' => $isValid,
            'msg' => $msg,
            'otpVerifyForm' => $otpVerifyForm,
            'paymentTransaction' => $paymentTransaction,
            'transactionDetail' => $transactionDetail,
            'walletInterview' => $walletInterview,
            'redirectUri' => $redirectUri,
        ], new PaymentContextView());
    }

    public function actionOtpVerifyFormValidate()
    {
        $otpVerifyForm = new OtpVerifyForm();
        $request = Yii::$app->getRequest();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isAjax && $request->isPost && $otpVerifyForm->load($request->post())) {
            return \yii\bootstrap4\ActiveForm::validate($otpVerifyForm);
        }
        return [];
    }

    public function actionCreatePayment()
    {

        $otpVerifyForm = new OtpVerifyForm();
        $request = Yii::$app->getRequest();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isAjax && $request->isPost && $otpVerifyForm->load($request->post()) && ($results = $otpVerifyForm->success())['success'] === true) {
            $results['data'] = ArrayHelper::merge($results['data'], [
                'returnUrl' => $otpVerifyForm->returnUrl,
                'cancelUrl' => $otpVerifyForm->cancelUrl,
                'token' => $otpVerifyForm->transactionCode,
                'order_code' => $otpVerifyForm->orderCode,
                'status' => $results['success']
            ]);
            return $results;
        }

        return ['success' => false, 'message' => $otpVerifyForm->getFirstErrors()];
    }

    public function actionRefreshOtp()
    {
        $otpVerifyForm = new OtpVerifyForm();
        $request = Yii::$app->getRequest();
        $out = ['success' => false, 'message' => 'can not refresh'];
        if ($otpVerifyForm->load($request->post())) {
            $response = $otpVerifyForm->refreshOtp();
            $out['success'] = $response['success'];
            $out['message'] = $response['message'];
            if ($out['success'] && $response['code'] === '0000') {
                $data = $response['data'];
                $receiveType = $data['receive_type'] ? 'email' : 'phone';
                $msg = 'Gửi lại otp thành công' . '. ' .
                    'Mã xác thực otp đã gửi tới' . ' ' . $receiveType . ': ' . $data['send_to'] . '. ' .
                    'OTP có hiệu lực trong' . ': ' .
                    Html::tag('span', $data['expired'], ['data-time-expired' => $data['expired_timestamp'], 'data-redirect-uri' => $otpVerifyForm->cancelUrl, 'class' => 'otp-expired-cooldown text-danger']) . '. ';
                $out['message'] = $msg;
            }


        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $out;
    }
}