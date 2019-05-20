<?php

namespace frontend\modules\payment\providers;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use common\models\WalletTransaction;
use frontend\modules\checkout\PaymentResponse;
use Yii;
use common\models\PaymentProvider;
use yii\base\BaseObject;
use common\components\XmlUtility;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\helpers\ArrayHelper;

class NganLuongProvider extends BaseObject implements PaymentProviderInterface
{

    public $submitUrl = 'https://www.nganluong.vn/checkout.api.nganluong.post.php';
    public $page;
    const PAGE_CHECK_AND_UPDATE = "CHECK_AND_UPDATE";

    public function create(Payment $payment)
    {
        $param = [];
        $yiiParams = Yii::$app->params['nganluong'];
        $param['function'] = 'SetExpressCheckout';
        // Product
        $param['merchant_id'] = $yiiParams['prod_trunggian']['ID'];
        $param['merchant_password'] = md5($yiiParams['prod_trunggian']['PASS']);
        $param['receiver_email'] = Yii::$app->params['nganluong']['prod_trunggian']['ACC'];
        // SandBox
        $this->submitUrl = str_replace('https://www.nganluong.vn', 'https://sandbox.nganluong.vn:8088/nl30', $this->submitUrl);
        $param['merchant_id'] = $yiiParams['sandbox']['ID'];
        $param['merchant_password'] = md5($yiiParams['sandbox']['PASS']);
        $param['receiver_email'] = $yiiParams['sandbox']['ACC'];

        $param['version'] = '3.1';
        $param['time_limit'] = 1440;

        $param['order_code'] = $payment->transaction_code;
        $param['return_url'] = $payment->return_url;
        $param['cancel_url'] = $payment->cancel_url;
        $param['order_description'] = '';
        $param['total_amount'] = $payment->total_amount;
        $param['fee_shipping'] = 0;
        $param['payment_method'] = $this->replaceMethod($payment->payment_method_name);
        $param['bank_code'] = $payment->payment_bank_code;
        if (strpos($payment->payment_bank_code, 'VISA') !== false) {
            $param['bank_code'] = 'VISA';
        } else if (strpos($payment->payment_bank_code, 'MASTER') !== false) {
            $param['bank_code'] = 'MASTER';
        } else if (strpos($payment->payment_bank_code, 'QRCODE') !== false) {
            $param['bank_code'] = 'ICB';
        }
        $param['buyer_fullname'] = $payment->customer_name;
        $param['buyer_email'] = $payment->customer_phone;
        $param['buyer_mobile'] = str_replace(['+84', '+084'], '0', $payment->customer_phone);
        $param['buyer_address'] = $payment->customer_address;

        if (in_array($payment->payment_bank_code, $this->checkBinCode())) {
            $param['event_code'] = $param['bank_code'] . '_CHECK_BIN';
        } else {
            $param['event_code'] = '';
        }
        $param['event_discount'] = 0;

        $resp = $this->callApi($this->submitUrl, $param);
        if ($resp == null || !is_array($resp) || !isset($resp['error_code']) || empty($resp['error_code']) || $resp['error_code'] != '00' || !isset($resp['token'])) {
            return new PaymentResponse(false, null, null, "Lỗi thanh toán trả về NL{$resp['error_code']}", "GET", $payment);
        }
        return new PaymentResponse(true, $resp['token'], isset($resp['checkout_url']) ? $resp['checkout_url'] : null, 'Call nl thành công', 'GET', $payment);

    }

    public function handle($data)
    {
        $success = false;
        $mess = "Check payment thành công";
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->request_content = $data;
        $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logCallback->transaction_code_request = "NGAN LUONG CALLBACK";
        $logCallback->store_id = 1;
        try{
            $yiiParams = Yii::$app->params['nganluong'];

            $orderCode = $data['order_code'];
            $transaction = WalletTransaction::findOne(['wallet_transaction_code' => $orderCode]);
            if(!$transaction){
                ///#Todo kiểm tra có trong transaction payment không
                $transaction = WalletTransaction::findOne(['wallet_transaction_code' => $orderCode]);
                if(!$transaction){
                    $logCallback->request_content = "Không tìm thấy transaction ở cả 2 bảng transaction!";
                    $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                    $logCallback->save(false);
                    return ReponseData::reponseMess(false,'Transaction không tồn tại') ;
                }
            }
            $param['function'] = 'GetTransactionDetail';
            // Product
            $param['merchant_id'] = $yiiParams['prod_trunggian']['ID'];
            $param['merchant_password'] = md5($yiiParams['prod_trunggian']['PASS']);
            $param['receiver_email'] = Yii::$app->params['nganluong']['prod_trunggian']['ACC'];
            // SandBox
            $this->submitUrl = str_replace('https://www.nganluong.vn', 'https://sandbox.nganluong.vn:8088/nl30', $this->submitUrl);
            $param['merchant_id'] = $yiiParams['sandbox']['ID'];
            $param['merchant_password'] = md5($yiiParams['sandbox']['PASS']);
            $param['receiver_email'] = $yiiParams['sandbox']['ACC'];

            $param['version'] = '3.1';
            $param['time_limit'] = 1440;

            $param['token'] = $data['token'];
            $logPaymentGateway = new PaymentGatewayLogs();
            $logPaymentGateway->transaction_code_ws = $orderCode;
            $logPaymentGateway->request_content = $param;
            $logPaymentGateway->transaction_code_request = $data['token'];
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CHECK_PAYMENT;
            $logPaymentGateway->url = $this->submitUrl;
            try{
                $resp = self::callApi($this->submitUrl, $param);
                $logPaymentGateway->payment_method = ArrayHelper::getValue($resp,'payment_method');
                $logPaymentGateway->payment_bank = ArrayHelper::getValue($resp,'bank_code');
                $logPaymentGateway->amount = ArrayHelper::getValue($resp,'total_amount');
                $logPaymentGateway->response_content = ($resp);
                $logPaymentGateway->response_time = date('Y-m-d H:i:s');
                $logPaymentGateway->create_time = date('Y-m-d H:i:s');
                $logPaymentGateway->store_id = 1;
                $logPaymentGateway->save(false);
                if(in_array($this->page, [self::PAGE_CHECK_AND_UPDATE])){
                    $mess = "Giao dịch đã được thanh toán không thành công!";
                    if($resp['error_code'] == 00){
                        $mess = "Giao dịch đã được thanh toán thành công!";
                        $success = true;
                        //#Todo update payment success cho transaction payment..
                    }
                }
            }catch (\Exception $exception){
                $logPaymentGateway->request_content = $exception->getMessage()." \n ".$exception->getFile() . " \n ".$exception->getTraceAsString();
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logPaymentGateway->save(false);
                return ReponseData::reponseMess(false,'Check payment thất bại') ;
            }
            $logCallback->response_content = "Success";
            $logCallback->save();
            return ReponseData::reponseMess($success,$mess) ;
        }catch (\Exception $e){
            $logCallback->request_content = $e->getMessage()." \n ".$e->getFile() . " \n ".$e->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseMess(false,'Call back thất bại') ;
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

    public function callApi($submitURL, $params = [])
    {
        $ch = curl_init($submitURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
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