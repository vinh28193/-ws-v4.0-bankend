<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-11
 * Time: 11:39
 */

namespace frontend\modules\payment\models;

use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\base\Model;

/**
 * Class OtpVerifyForm
 * @package common\models\payment\wallet
 */
class OtpVerifyForm extends Model
{

    const TYPE_TRANSACTION_PAY_ORDER = 'PAY_ORDER';
    const TYPE_TRANSACTION_WITHDRAW = 'WITH_DRAW';
    /**
     * @var string;
     */
    public $typeTransaction;
    /**
     * @var string
     */
    public $otpCode;

    /**
     * @var integer
     */
    public $otpReceive;

    /**
     * @var string
     */
    public $captcha;

    /**
     * @var string
     */
    public $transactionCode;

    public $orderCode;
    /**
     * @var
     */
    public $cancelUrl;

    /**
     * @var
     */
    public $returnUrl;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
        $this->returnUrl = $this->createReturnUrl();


    }

    public function rules()
    {
        return [
            [['otpCode', 'captcha', 'transactionCode', 'orderCode'], 'required'],
            ['otpReceive', 'integer'],
            [['transactionCode', 'orderCode'], 'string'],
            ['captcha', 'captcha', 'captchaAction' => '/payment/wallet/captcha'],
            ['otpCode', 'validateOtp'],
            ['otpReceive', 'filter', 'filter' => function ($value) {
                return (string)$value;
            }],
            [['cancelUrl', 'returnUrl'], 'url'],
            [['returnUrl', 'cancelUrl'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'captcha' => 'Mã xác nhận',
            'otpCode' => 'Mã OTP',
            'otpReceive' => 'OPT Receive Type'
        ];
    }

    public function validateOtp($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            //Todo send http request to api.

            $walletService = new WalletService([
                'transaction_code' => $this->transactionCode,
                'otp_code' => $this->$attribute
            ]);
            $responseApi = $walletService->validateOtp();
            if (!$responseApi['success']) {
                $this->addError($attribute, $responseApi['message']);
            }
        }
    }

    public function detail()
    {
        $walletService = new WalletService(['transaction_code' => $this->transactionCode]);
        $transaction = $walletService->transactionDetail();
        if ($transaction['success']) {
            return $transaction['data'];
        }
        return [];
    }

    public function success()
    {
        $walletService = new WalletService(['transaction_code' => $this->transactionCode]);
        return $walletService->transactionSuccess();
    }

    public function verify()
    {
        if (!$this->validate()) {
            return false;
        }
        return true;
    }

    public function refreshOtp()
    {
        $walletClient = new WalletService([
            'transaction_code' => $this->transactionCode,
            'otp_type' => $this->otpReceive,
        ]);
        return $walletClient->refreshOtp();
    }

    protected function createReturnUrl()
    {
        return PaymentService::createReturnUrl(43);
    }
}