<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-11
 * Time: 11:39
 */

namespace frontend\modules\payment\models;

use common\components\RedisLanguage;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

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
    public $optCode;

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

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!$this->transactionCode) {
            throw new InvalidConfigException(self::className() . '::$transactionCode must be set');
        }

    }

    public function rules()
    {
        return [
            [['optCode', 'captcha'], 'required'],
            ['otpReceive', 'integer'],
            ['captcha', 'captcha', 'captchaAction' => '/payment/wallet/captcha'],
            ['optCode', 'validateOtp']
        ];
    }

    public function attributeLabels()
    {
        return [
            'captcha' => 'Mã xác nhận',
            'optCode' => 'Mã OTP',
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
            'otp_type' => $this->otpReceive ? '1' : '0',
        ]);
        return $walletClient->refreshOtp();

    }
}