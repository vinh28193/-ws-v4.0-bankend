<?php

namespace frontend\modules\payment\providers\nganluong\ver3_1;

use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentResponse;
use frontend\modules\payment\PaymentService;
use Yii;
use Exception;
use yii\base\BaseObject;
use common\models\PaymentProvider;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use common\components\XmlUtility;
use yii\helpers\ArrayHelper;

class NganLuongProvider extends BaseObject implements PaymentProviderInterface
{

    public $submitUrl = 'https://www.nganluong.vn/checkout.api.nganluong.post.php';
    public $page;
    const PAGE_CHECK_AND_UPDATE = "CHECK_AND_UPDATE";

    public function create(Payment $payment)
    {
        $param = [];
        $yiiParams = PaymentService::getClientConfig('nganluong_ver3_1');
        $param['function'] = 'SetExpressCheckout';

        $this->submitUrl = $yiiParams['URL'];
        $param['merchant_id'] = $yiiParams['ID'];
        $param['merchant_password'] = md5($yiiParams['PASS']);
        $param['receiver_email'] = $yiiParams['ACC'];

        $param['version'] = '3.1';
        $param['time_limit'] = 1440;

        $param['order_code'] = $payment->transaction_code;
        $param['return_url'] = $payment->return_url;
        $param['cancel_url'] = $payment->cancel_url;
        $param['order_description'] = "Thanh toan cho cac ma don: {$payment->getOrderCodes()}";
        $param['total_amount'] = $payment->getTotalAmountDisplay();
        $param['fee_shipping'] = 0;
        if (strpos($payment->payment_method_name, 'ATM_') !== false) {
            $param['payment_method'] = 'ATM_ONLINE';
        }else {
            $param['payment_method'] = $this->replaceMethod($payment->payment_method_name);
        }

        $param['bank_code'] = $payment->payment_bank_code;
        if (strpos($payment->payment_bank_code, 'VISA') !== false) {
            $param['bank_code'] = 'VISA';
        } else if (strpos($payment->payment_bank_code, 'MASTER') !== false) {
            $param['bank_code'] = 'MASTER';
        } else if (strpos($payment->payment_bank_code, 'ATM_') !== false) {
            $param['bank_code'] = str_replace('ATM_','',$payment->payment_bank_code);
        }
        $param['buyer_fullname'] = $payment->customer_name;
        $param['buyer_email'] = $payment->customer_email;
        $param['buyer_mobile'] = str_replace(['+84', '+084'], '0', $payment->customer_phone);
        $param['buyer_address'] = $payment->customer_address;

        if (in_array($payment->payment_bank_code, $this->checkBinCode())) {
            $param['event_code'] = $param['bank_code'] . '_CHECK_BIN';
        } else {
            $param['event_code'] = '';
        }
        $param['event_discount'] = 0;

        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
        $logPaymentGateway->request_content = $param;
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;
        $logPaymentGateway->url = $this->submitUrl;
        $mess = Yii::t('frontend','Success');
        $success = true;
        try {
            $resp = $this->callApi($this->submitUrl, $param);
            $logPaymentGateway->payment_method = ArrayHelper::getValue($param, 'payment_method');
            $logPaymentGateway->payment_bank = ArrayHelper::getValue($param, 'bank_code');
            $logPaymentGateway->amount = ArrayHelper::getValue($param, 'total_amount');
            $logPaymentGateway->response_content = ($resp);
            $logPaymentGateway->response_time = date('Y-m-d H:i:s');
            $logPaymentGateway->create_time = date('Y-m-d H:i:s');
            $logPaymentGateway->store_id = 1;
            $logPaymentGateway->save(false);

            if ($resp == null || !is_array($resp) || !isset($resp['error_code']) || empty($resp['error_code']) || $resp['error_code'] != '00' || !isset($resp['token'])) {
                $err = $resp['error_code'];
                if (isset($resp['description']) && $resp['description']) {
                    $err .= (':'.$resp['description'] . '.');
                }
                $mess = Yii::t('frontend','Payment gateway error `{message}`',[
                    'message' => $err
                ]);
                $success = false;
            }
            return new PaymentResponse($success, $mess,'nganluong' ,$payment->transaction_code, $payment->getOrderCodes(),PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $resp['token'], $resp['error_code'], isset($resp['checkout_url']) ? $resp['checkout_url'] : null);
        } catch (Exception $exception) {
            $logPaymentGateway->request_content = $param;
            $logPaymentGateway->response_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
            $logPaymentGateway->save(false);
            return new PaymentResponse(false, Yii::t('frontend','Payment gateway error `{message}`',[
                'message' => Yii::t('yii','An internal server error occurred.')
            ]),'nganluong');
        }
    }

    public function handle($data)
    {

        $yiiParams = PaymentService::getClientConfig('nganluong_ver3_1');

        $orderCode = $data['order_code'];

        $param['function'] = 'GetTransactionDetail';

        $this->submitUrl = $yiiParams['URL'];
        $param['merchant_id'] = $yiiParams['ID'];
        $param['merchant_password'] = md5($yiiParams['PASS']);
        $param['receiver_email'] = $yiiParams['ACC'];

        $param['version'] = '3.1';
        $param['time_limit'] = 1440;

        $param['token'] = $data['token'];

        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $orderCode;
        $logPaymentGateway->request_content = $param;
        $logPaymentGateway->transaction_code_request = $data['token'];
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logPaymentGateway->url = $this->submitUrl;
        try {
            $transaction = PaymentService::findParentTransaction($orderCode);
            if ($transaction === null && ($transaction = PaymentService::findChildTransaction($orderCode)) === null) {
                $logPaymentGateway->request_content = "Không tìm thấy transaction ở cả 2 bảng transaction!";
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logPaymentGateway->save(false);
                return new PaymentResponse(false, 'Transaction không tồn tại','nganluong');
            }
            $resp = self::callApi($this->submitUrl, $param);
            $logPaymentGateway->payment_method = ArrayHelper::getValue($resp, 'payment_method');
            $logPaymentGateway->payment_bank = ArrayHelper::getValue($resp, 'bank_code');
            $logPaymentGateway->amount = ArrayHelper::getValue($resp, 'total_amount');
            $logPaymentGateway->response_content = ($resp);
            $logPaymentGateway->response_time = date('Y-m-d H:i:s');
            $logPaymentGateway->create_time = date('Y-m-d H:i:s');
            $logPaymentGateway->store_id = 1;
            $logPaymentGateway->save(false);
            if ($resp['error_code'] != '00') {
                return new PaymentResponse(false, 'failed','nganluong');
            }
            $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
            $transaction->save(false);
            return new PaymentResponse(true, 'Giao dịch thanh toán không thành công!','nganluong', $transaction);
        } catch (\Exception $exception) {
            $logPaymentGateway->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logPaymentGateway->save(false);
            return new PaymentResponse(false, 'Check payment thất bại','nganluong');
        }


    }

    public function replaceMethod($method)
    {
        return str_replace($this->methodPrefix(), '', str_replace('MASTER', 'VISA', $method));
    }

    public function methodPrefix()
    {
        return ['VPB_', 'VCB_', 'HDB_', 'TCB_', 'JCB_', 'FEC_', 'MSB_', 'TPB_', 'SCB_', 'SGCB_'];
    }

    public function checkBinCode()
    {
        return [
            'VCB_VISA', 'VCB_MASTER', 'VPB_VISA', 'VPB_MASTER', 'HDB_VISA', 'HDB_MASTER', 'TCB_VISA',
            'TCB_MASTER', 'JCB_VISA', 'JCB_MASTER', 'FEC_MASTER', 'MSB_VISA', 'MSB_MASTER', 'TPB_VISA',
            'TPB_MASTER', 'SCB_VISA', 'SCB_MASTER', 'SGCB_VISA', 'SGCB_MASTER'
        ];
    }

    public function callApi($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildParams($params));
        $result = curl_exec($ch);
        curl_close($ch);
        return XmlUtility::xmlToArray($result);
    }

    private function buildParams($params)
    {
        $query = '';
        $i = 1;
        foreach ($params as $key => $val) {
            $query .= ($i == 1 ? '' : '&') . $key . '=' . $val;
            $i++;
        }
        return $query;
    }

    protected function getProvider()
    {
        return PaymentProvider::findOne(22);
    }

}