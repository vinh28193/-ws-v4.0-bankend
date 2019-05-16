<?php

namespace common\payment\providers;

use frontend\modules\checkout\PaymentResponse;
use Yii;
use common\models\PaymentProvider;
use yii\base\BaseObject;
use common\components\XmlUtility;
use frontend\modules\checkout\Payment;
use frontend\modules\checkout\PaymentProviderInterface;

class NganLuongProvider extends BaseObject implements PaymentProviderInterface
{

    public $submitUrl = 'https://www.nganluong.vn/checkout.api.nganluong.post.php';


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
        $yiiParams = Yii::$app->params['nganluong'];

        $orderCode = $data['order_code'];
        $param['function'] = 'GetTransactionDetail';
        // Product
        $param['merchant_id'] = $yiiParams['prod_trunggian']['ID'];
        $param['merchant_password'] = md5($yiiParams['prod_trunggian']['PASS']);
        $param['receiver_email'] = Yii::$app->params['nganluong']['prod_trunggian']['ACC'];
        // SandBox
        $this->submitUrl = str_replace('https://www.nganluong.vn', 'https://sandbox.nganluong.vn:8088/nl30', $submitUrl);
        $param['merchant_id'] = $yiiParams['sandbox']['ID'];
        $param['merchant_password'] = md5($yiiParams['sandbox']['PASS']);
        $param['receiver_email'] = $yiiParams['sandbox']['ACC'];

        $param['version'] = '3.1';
        $param['time_limit'] = 1440;

        $param['token'] = $data['token'];

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