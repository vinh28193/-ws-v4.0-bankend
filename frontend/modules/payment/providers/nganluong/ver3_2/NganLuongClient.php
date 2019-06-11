<?php


namespace frontend\modules\payment\providers\nganluong\ver3_2;

use frontend\modules\payment\PaymentService;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class NganLuongClient extends BaseObject
{

    public $env = 'product';

    public $baseUrl = 'https://www.nganluong.vn';

    public $merchant_id = '';

    public $merchant_password;

    public $version = '3.2';

    public $function;

    public $receiver_email;

    public $time_limit = 1440;

    public function init()
    {
        parent::init();
        if (($yiiParams = PaymentService::getClientConfig('nganluong_ver3.2', $this->env)) !== null && !empty($yiiParams)) {
            Yii::configure($this, $yiiParams); // reconfig with env
        }

    }

    private $_params;

    public function getParams()
    {
        if ($this->_params === null) {
            $this->_params = new  NganLuongParamCollection();
            $this->_params->set('merchant_id', $this->merchant_id);
            $this->_params->set('merchant_password', $this->merchant_password);
            $this->_params->set('version', $this->version);
            $this->_params->set('function', $this->function);
            $this->_params->set('receiver_email', $this->receiver_email);
        }
        return $this->_params;
    }

    public function getAPIUrl()
    {
        return $this->baseUrl . '/paygate.weshop.php';
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
        return NganluongHelper::normalizeResponse($result);
    }

    public function GetRequestField($bankCode, $paymentMethod = null)
    {
        if ($paymentMethod === null) {
            $paymentMethod = NganluongHelper::getPaymentMethod($bankCode);
        }
        $this->getParams()->setDefault('function', 'GetRequestField');
        $this->getParams()->setDefault('bank_code', $bankCode);
        $this->getParams()->setDefault('Payment_method', $paymentMethod);
        return $this->callApi();
    }

    public function AuthenTransaction($token, $otp, $auth_url)
    {
        $this->function = 'AuthenTransaction';
    }

    public function SetExpressCheckout($params)
    {
        $this->function = 'SetExpressCheckout';

    }

    public function GetTransactionDetail($token)
    {
        $this->function = 'GetTransactionDetail';
    }
}