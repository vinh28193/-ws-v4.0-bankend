<?php

namespace frontend\modules\payment\providers\alepay;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

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
        try {
            $param = [];
            $param['orderCode'] = $payment->transaction_code;
            // Product
            $param['orderDescription'] = "Giao dịch cho mã " . $payment->transaction_code . " trên hệ thống";
            $param['amount'] = ($payment->total_amount - $payment->total_discount_amount);
            $param['buyerCity'] = $payment->customer_city;
            $param['buyerName'] = $payment->customer_phone;
            $param['buyerEmail'] = $payment->customer_email;
            $param['buyerPhone'] = $payment->customer_phone;

            $param['buyerAddress'] = $payment->customer_address;
            $param['totalItem'] = count($payment->carts);

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
            $data = [];
            try {
                $resp = $this->getAlepayClient()->requestOrder($data);
                $logPaymentGateway->payment_method = ArrayHelper::getValue($param, 'paymentMethod');
                $logPaymentGateway->payment_bank = ArrayHelper::getValue($param, 'bankCode');
                $logPaymentGateway->amount = ArrayHelper::getValue($param, 'installment');
                $logPaymentGateway->response_content = ($resp);
                $logPaymentGateway->response_time = date('Y-m-d H:i:s');
                $logPaymentGateway->create_time = date('Y-m-d H:i:s');
                $logPaymentGateway->store_id = 1;
                $logPaymentGateway->save(false);

                if ($resp == null || !is_array($resp) || !isset($resp['error_code']) || empty($resp['error_code']) || $resp['error_code'] != '00' || !isset($resp['token'])) {
                    $mess = "Lỗi thanh toán trả về NL" . $resp['error_code'];
                    $success = false;
                }
                $request_content['api'] = $this->submitUrl;
                $request_content['form'] = $param;
                $data = [
                    'code' => $payment->transaction_code,
                    'status' => isset($resp['error_code']) ? $resp['error_code'] : null,
                    'token' => $resp['token'],
                    'checkoutUrl' => isset($resp['checkout_url']) ? $resp['checkout_url'] : null,
                    'method' => 'GET',
                ];
            } catch (\Exception $exception) {
                $logPaymentGateway->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                $logPaymentGateway->save(false);
                return ReponseData::reponseArray(false, 'Check payment thất bại');
            }
            $logCallback->response_content = "Success";
            $logCallback->save();
            return ReponseData::reponseArray($success, $mess, $data);
        } catch (\Exception $exception) {
            $logCallback->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseArray(false, 'thất bại');
        }

    }

    public function handle($data)
    {
        // TODO: Implement handle() method.
    }

    public function getAlepayClient()
    {
        return new AlepayClient();
    }
}