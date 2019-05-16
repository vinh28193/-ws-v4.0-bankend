<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-11
 * Time: 11:39
 */

namespace common\models\payment\wallet;

use common\components\RedisLanguage;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

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
     * @var \common\models\db\WalletClient
     */
    public $walletClient;
    /**
     * @var \common\models\weshop\Website
     */
    public $website;

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
        if (!$this->getHttpClient() instanceof \common\models\db\WalletClient) {
            throw new InvalidConfigException(self::className() . '::$httpClient must be instance \common\models\db\WalletClient');
        }
        if (!$this->getWebsite() instanceof \common\models\weshop\Website) {
            throw new InvalidConfigException(self::className() . '::$httpClient must be instance \common\models\weshop\Website');
        }

    }

    public function rules()
    {
        return [
            [['optCode', 'captcha'], 'required'],
            ['otpReceive', 'integer'],
            ['captcha', 'captcha', 'captchaAction' => '/account/wallet/captcha'],
            ['optCode', 'validateOtp']
        ];
    }

    public function attributeLabels()
    {
        return [
            'captcha' => RedisLanguage::getLanguageByKey('wallet_changeemail_form_captcha_name', 'Mã xác nhận'),
            'optCode' => RedisLanguage::getLanguageByKey('wallet_active_form_otp', 'Mã OTP:'),
            'otpReceive' => 'OPT Receive Type'
        ];
    }

    /**
     * getter
     * @return \common\models\db\WalletClient
     */
    public function getHttpClient()
    {
        if (!$this->walletClient) {
            $this->walletClient = Yii::$app->authClientCollection->getClient('wallet');
        }
        return $this->walletClient;
    }

    /**
     * getter
     * @return \common\models\weshop\Website
     */
    public function getWebsite()
    {
        if (!$this->website) {
            $this->website = (new \common\models\weshop\Website())->load();
        }
        return $this->website;
    }

    public function validateOtp($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            //Todo send http request to api.

            $requestApi = $this->walletClient->createApiRequest();
            $requestApi->setMethod('POST');
            $requestApi->setUrl('transaction/verify-opt');
            $requestApi->setData([
                'transaction_code' => $this->transactionCode,
                'otp_code' => $this->$attribute
            ]);
            $responseApi = $requestApi->send();
            if (!$responseApi->isOk) {
                $this->addError($attribute, 'Cannot connect to server,please try again');
            }
            $responseApi = $responseApi->getData();
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


        //Todo check $responseWalletApi Error Code
    }


    public function refreshOtp()
    {
        $requestWalletApi = $this->walletClient->createApiRequest();
        $requestWalletApi->setMethod('POST');
        $requestWalletApi->setUrl('transaction/refresh-otp');
        $requestWalletApi->setData([
            'transaction_code' => $this->transactionCode,
            'otp_receive_type' => $this->otpReceive ? '1' : '0',
        ]);
        $responseWalletApi = $requestWalletApi->send();
        if (!$responseWalletApi->isOk) {
            return [
                'success' => false,
                'message' => 'can not connect to server',
                'data' => null,
            ];
        }
        $responseWalletApi = $responseWalletApi->getData();
        return $responseWalletApi;

    }
}