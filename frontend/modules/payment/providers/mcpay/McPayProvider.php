<?php

namespace frontend\modules\payment\providers\mcpay;

use frontend\modules\payment\PaymentService;
use Yii;
use Exception;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class McPayProvider extends BaseObject implements PaymentProviderInterface
{

    public $baseUrl;
    public $merchant;
    public $verifyKey;

    public $amount;
    public $orderId;

    public $billName;
    public $billEmail;
    public $billMobile;
    public $billDesc;
    public $returnUrl;
    public $langCode;


    public function init()
    {
        parent::init();
        if (($yiiParams = PaymentService::getClientConfig('mcpay')) !== null && !empty($yiiParams)) {
            Yii::configure($this, $yiiParams); // reconfig with env
        }
        if($this->baseUrl === null){
            throw new InvalidConfigException('baseUrl can not be null');
        }
    }

    public function create(Payment $payment)
    {
        $this->orderId = $payment->transaction_code;
        $this->billName = $payment->customer_name;
        $this->billEmail = $payment->customer_email;
        $this->billMobile = $payment->customer_phone;
        $this->billDesc = '';
        $this->amount = (string)$payment->getTotalAmountDisplay();
        $this->returnUrl = $payment->return_url;
        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
        $logPaymentGateway->request_content = $this->getQueryParams();
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;

        $checkoutUrl = $this->createCheckOutUrl();
        return new PaymentResponse(true, 'McPay success', 'mcpay',$payment->transaction_code, null,PaymentResponse::TYPE_NORMAL, PaymentResponse::METHOD_GET, $payment->transaction_code, 'ok', $checkoutUrl);
    }

    public function handle($data)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->request_content = $data;
        $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logCallback->transaction_code_request = "MC PAY CALLBACK";
        $logCallback->store_id = 1;
        try {
            $tranID = $data['tranID'];
            $orderid = $data['orderid'];
            $status = $data['status'];
            $domain = $data['domain'];
            $amount = $data['amount'];
            $currency = $data['currency'];
            $appcode = $data['appcode'];
            $paydate = $data['paydate'];
            $skey = $data['skey'];
            if (($transaction = PaymentTransaction::findOne(['transaction_code' => $orderid])) === null) {
                $logCallback->request_content = "Không tìm thấy transaction ở cả 2 bảng transaction!";
                $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logCallback->save(false);
                return new PaymentResponse(false, 'Transaction không tồn tại', 'mcpay');
            }
            $key0 = md5($tranID . $orderid . $status . $domain . $amount . $currency);
            $key1 = md5($paydate . $domain . $key0 . $appcode . $this->verifyKey);
            $success = false;
            $mess = "Giao dịch thanh toán không thành công!";
            if ($skey == $key1 && $status === '00') {
                $success = true;
                $mess = "Giao dịch đang được cho duyệt";
            }
            if ($success) {
                $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $transaction->save();
            }
            $logCallback->response_content = null;
            $logCallback->save();
            return new PaymentResponse($success, $mess,'mcpay', $transaction);
        } catch (Exception $e) {
            $logCallback->request_content = $e->getMessage() . " \n " . $e->getFile() . " \n " . $e->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return new PaymentResponse(false, 'Call back thất bại', 'mcpay');
        }

    }

    public function createCheckOutUrl()
    {
        return $this->baseUrl . '/pay/' . $this->merchant . '?' . $this->getQueryParams();
    }

    protected function getQueryParams()
    {
        return "amount=" . $this->amount . "&" .
            "orderid=" . urlencode($this->orderId) . "&" .
            "bill_name=" . urlencode($this->billName) . "&" .
            "bill_email=" . urlencode($this->billEmail) . "&" .
            "bill_mobile=" . urlencode($this->billMobile) . "&" .
            "bill_desc=" . urlencode($this->billDesc) . "&" .
            "country=ID&" .
            "returnurl=" . urlencode($this->returnUrl) . "&" .
            "vcode=" . $this->renderVcode() . "&" .
            "cur=IDR&" .
            "langcode=en";

    }

    protected function renderVcode()
    {
        return md5((string)$this->amount & $this->merchant & $this->orderId & $this->verifyKey);
    }
}