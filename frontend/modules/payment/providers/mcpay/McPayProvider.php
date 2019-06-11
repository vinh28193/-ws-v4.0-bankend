<?php

namespace frontend\modules\payment\providers\mcpay;

use Yii;
use Exception;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;
use yii\base\BaseObject;

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
        return new PaymentResponse(true, 'McPay success', $payment->transaction_code, PaymentResponse::TYPE_NORMAL, PaymentResponse::METHOD_GET, $payment->transaction_code, $checkoutUrl);
    }

    public function handle($data)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->request_content = $data;
        $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logCallback->transaction_code_request = "NGAN LUONG CALLBACK";
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
                return new PaymentResponse(false, 'Transaction không tồn tại');
            }
            $key0 = md5($tranID . $orderid . $status . $domain . $amount . $currency);
            $key1 = md5($paydate . $domain . $key0 . $appcode . $this->verify_key);
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
            return new PaymentResponse($success, $mess, $transaction);
        } catch (Exception $e) {
            $logCallback->request_content = $e->getMessage() . " \n " . $e->getFile() . " \n " . $e->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return new PaymentResponse(false, 'Call back thất bại');
        }

    }

    public function createCheckOutUrl()
    {
        return $this->url . '?' . http_build_query($this->getParams());
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