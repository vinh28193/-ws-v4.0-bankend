<?php

namespace frontend\modules\payment\providers\mcpay;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class McPayProvider extends BaseObject implements PaymentProviderInterface
{

    public $merchant = 'weshopdev';
    public $url = 'https://mcbill.sandbox.id.mcpayment.net/pay/weshopdev';
    public $verify_key = '92ccabfc283a8d6b8a5c0d3b656ad05e';

    public $amount;
    public $orderId;

    public $billName;
    public $billEmail;
    public $billMobile;
    public $billDesc;
    public $returnUrl;
    public $langCode;

    public function create(Payment $payment)
    {
        $this->orderId = $payment->transaction_code;
        $this->billName = $payment->customer_name;
        $this->billEmail = $payment->customer_email;
        $this->billMobile = $payment->customer_phone;
        $this->billDesc = '';

        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
        $logPaymentGateway->request_content = $this->getParams();
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;

        $checkoutUrl = $this->createCheckOutUrl();


        return ReponseData::reponseArray(true, 'McPay success', [
            'code' => $payment->transaction_code,
            'status' => 1,
            'token' => $payment->transaction_code,
            'checkoutUrl' => $checkoutUrl,
            'method' => 'GET',
        ]);

    }

    public function handle($data)
    {
        // TODO: Implement handle() method.
    }

    public function createCheckOutUrl()
    {
        return Url::current([$this->url, $this->getParams()],false);
    }

    protected function getParams()
    {
        return [
            'amount' => $this->amount,
            'orderid' => $this->orderId,
            'bill_name' => $this->billName,
            'bill_email' => $this->billEmail,
            'bill_mobile' => $this->billMobile,
            'bill_desc' => $this->billDesc,
            'country' => 'ID',
            'returnurl' => $this->returnUrl,
            'vcode' => $this->renderVcode(),
            'cur' => 'IDR',
            'langcode' => 'en',
        ];
    }

    protected function renderVcode()
    {
        return md5($this->amount & $this->merchant & $this->orderId & $this->verify_key);
    }
}