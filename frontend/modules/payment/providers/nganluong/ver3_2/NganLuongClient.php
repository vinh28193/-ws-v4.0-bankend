<?php


namespace frontend\modules\payment\providers\nganluong\ver3_2;

use frontend\modules\payment\PaymentService;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class NganLuongClient extends BaseObject
{

    public $summitUrl;
    public $merchant_id;
    public $merchant_password;
    public $receiver_email;
    public $version = '3.2';
    public $time_limit = 1440;

    public function init()
    {
        parent::init();
        if (($yiiParams = PaymentService::getClientConfig('nganluong_ver3_2')) !== null && !empty($yiiParams)) {
            Yii::configure($this, $yiiParams); // reconfig with env
        }

    }

    private $_params;

    public function getParams()
    {
        if ($this->_params === null) {
            $this->_params = new  NganLuongParamCollection();
            $this->_params->setDefault('merchant_id', $this->merchant_id);
            $this->_params->setDefault('merchant_password', md5($this->merchant_password));
            $this->_params->setDefault('version', $this->version);
            $this->_params->setDefault('receiver_email', $this->receiver_email);
        }
        return $this->_params;
    }

    public function getAPIUrl()
    {
        return $this->summitUrl;
    }

    public function callApi()
    {
        $ch = curl_init($this->getAPIUrl());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getParams()->create());
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($this->getAPIUrl(),$this->getParams()->toArray(),NganluongHelper::normalizeResponse($result));die;
        return NganluongHelper::normalizeResponse($result);
    }

    public function GetRequestField($bankCode, $paymentMethod = null)
    {
        if ($paymentMethod === null) {
            $paymentMethod = NganluongHelper::getPaymentMethod($bankCode);
        }
        $this->getParams()->setDefault('function', 'GetRequestField');
        $this->getParams()->setDefault('bank_code', NganluongHelper::replaceBankCode($bankCode));
        $this->getParams()->setDefault('payment_method', $paymentMethod);
        return $this->callApi();
    }

    public function AuthenTransaction($token, $otp, $auth_url)
    {
        $this->getParams()->setDefault('function', 'AuthenTransaction');
    }

    public function SetExpressCheckout()
    {
        $this->getParams()->setDefault('function', 'SetExpressCheckout');
        return $this->callApi();
    }

    public function GetTransactionDetail($token)
    {
        $this->getParams()->setDefault('function', 'GetTransactionDetail');
    }
}