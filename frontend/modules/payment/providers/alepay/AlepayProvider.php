<?php

namespace frontend\modules\payment\providers\alepay;


use Yii;
use Exception;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;

class AlepayProvider extends BaseObject implements PaymentProviderInterface
{


    public function create(Payment $payment)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->type = PaymentGatewayLogs::TYPE_CREATED;
        $logCallback->transaction_code_request = "ALEPAY CREATE PAYMENT";
        $logCallback->store_id = 1;

        $param = [];
        $param['orderCode'] = $payment->transaction_code;
        // Product
        $param['orderDescription'] = "Giao dịch cho mã " . $payment->transaction_code . " trên hệ thống";
        $param['amount'] = $payment->getTotalAmountDisplay();
        $param['currency'] = 'VND';

        $param['buyerCountry'] = 'VN';
        $param['buyerCity'] = $payment->customer_city;
        $param['buyerAddress'] = $payment->customer_address;
        $param['buyerName'] = $payment->customer_name;
        $param['buyerEmail'] = $payment->customer_email;
        $param['buyerPhone'] = $payment->customer_phone;

        $param['totalItem'] = count($payment->getOrders());

        $param['checkoutType'] = 0;
        $param['paymentHours'] = 6;

        $param['returnUrl'] = $payment->return_url;
        $param['cancelUrl'] = $payment->cancel_url;

        if (!empty($payment->installment_bank) && !empty($payment->installment_method) && !empty($payment->installment_month)) {
            $param['bankCode'] = $payment->installment_bank;
            $param['paymentMethod'] = $payment->installment_method;
            $param['month'] = $payment->installment_month;
            $param['installment'] = true;
        } else {
            $param['month'] = 1;
            $param['installment'] = false;
        }

        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
        $logPaymentGateway->request_content = $param;
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;
        $logPaymentGateway->url = $this->getAlepayClient()->baseUrl;
        $mess = "Giao dịch thanh toán không thành công!";
        $success = true;
        try {
            $resp = $this->getAlepayClient()->requestOrder($param);
            $logPaymentGateway->payment_method = ArrayHelper::getValue($param, 'paymentMethod');
            $logPaymentGateway->payment_bank = ArrayHelper::getValue($param, 'bankCode');
            $logPaymentGateway->amount = ArrayHelper::getValue($param, 'amount');
            $logPaymentGateway->response_content = ($resp);
            $logPaymentGateway->response_time = date('Y-m-d H:i:s');
            $logPaymentGateway->create_time = date('Y-m-d H:i:s');
            $logPaymentGateway->store_id = 1;
            $logPaymentGateway->save(false);

            if ($resp['success'] === false) {
                return new PaymentResponse(false, "Lỗi thanh toán trả về Alepay" . $resp['code'],'alepay');
            }
            $data = Json::decode($resp['data'], true);
            return new PaymentResponse(true, $mess, 'alepay',$payment->transaction_code, PaymentResponse::TYPE_NORMAL, PaymentResponse::METHOD_GET, ArrayHelper::getValue($data, 'token'), $resp['code'], ArrayHelper::getValue($data, 'checkoutUrl'));
        } catch (\Exception $exception) {
            $logPaymentGateway->response_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
            $logPaymentGateway->save(false);
            return new PaymentResponse(false, $exception->getMessage(),'alepay');
        }

    }

    public
    function handle($data)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->request_content = $data;
        $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logCallback->transaction_code_request = "NGAN LUONG CALLBACK";
        $logCallback->store_id = 1;
        try {

            $token = $data['data'];

            $resp = $this->checkTransaction($token);

            $transactionInfo = $this->getAlepayClient()->getTransactionInfo($resp['data'])['data'];
            $transactionInfo = Json::decode($transactionInfo, true);

            $orderCode = $transactionInfo['orderCode'];
            if (($transaction = PaymentTransaction::findOne(['transaction_code' => $orderCode])) === null) {
                $logCallback->request_content = "Không tìm thấy transaction ở cả 2 bảng transaction!";
                $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logCallback->save(false);
                return new PaymentResponse(false, 'Transaction không tồn tại','alepay');
            }
            $success = false;
            $mess = "Giao dịch thanh toán không thành công!";
            if ($transactionInfo['status'] === '155') {
                $success = true;
                $mess = "Giao dịch đang được cho duyệt";
            } elseif ($transactionInfo['status'] === '000') {
                $success = true;
                $mess = "Giao dịch đã được thanh toán thành công!";
            }
            if ($success) {
                $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $transaction->save();
            }
            $logCallback->response_content = $transactionInfo;
            $logCallback->save();
            return new PaymentResponse(true, $mess,'alepay', $transaction);
        } catch (Exception $e) {
            $logCallback->request_content = $e->getMessage() . " \n " . $e->getFile() . " \n " . $e->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return new PaymentResponse(false, 'alepay','Call back thất bại');
        }


    }

    public
    function getAlepayClient()
    {
        return new AlepayClient();
    }

    public
    function checkTransaction($data)
    {
        $data = base64_decode($data);
        $data = $this->getAlepayClient()->getSecurity()->decrypt($data);
        return Json::decode($data, true);
    }

}