<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 20/08/2018
 * Time: 13:38
 */

namespace common\models\payment\vietnam\alepaylib;


use common\components\Cache;
use common\models\model\PaymentProvider;
use common\models\model_logs\PaymentLog;
use Illuminate\Database\Eloquent\Model;

class AlepayGate
{
    protected $alepayUtils;
    //protected $publicKey = "";
    protected $checksumKey = "";
    protected $apiKey = "";
    protected $env = true;

    protected $callbackUrl = "http://localhost/ebay/ebay/public/service/demo/test.json";
    public $binCode = '';

    public $log;
    public $merchant_pro = array(
        // url live
        'requestPayment' => 'https://alepay.vn/checkout/v1/request-order',
        'calculateFee' => 'https://alepay.vn/checkout/v1/calculate-fee',
        'getTransactionInfo' => 'https://alepay.vn/checkout/v1/get-transaction-info',
        'requestCardLink' => 'https://alepay.vn/checkout/v1/request-profile',
        'tokenizationPayment' => 'https://alepay.vn/checkout/v1/request-tokenization-payment',
        'cancelCardLink' => 'https://alepay.vn/checkout/v1/cancel-profile',
        'installmentinfo' => 'https://alepay.vn//checkout/v1/get-installment-info',
        'apiKey'=>'',
        'checksumKey'=>'',
        'encryptKey'=>'',
        'callbackUrl'=>''
    );

    public $merchant_dev = array(
        'requestPayment' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-order',
        'calculateFee' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/calculate-fee',
        'getTransactionInfo' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/get-transaction-info',
        'requestCardLink' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-profile',
        'tokenizationPayment' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-tokenization-payment',
        'cancelCardLink' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/cancel-profile',
        'installmentinfo' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/get-installment-info',
        'apiKey' => 'g84sF7yJ2cOrpQ88VbdZoZfiqX4Upx',
        'checksumKey' => 'lXntf6CIZbSgzMqTz1nQ11jPKhGfsF',
        'encryptKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKWYg7jKrTqs83iIvYxlLgMqIy4MErNsoBKU2MHaG5ccntzGeNcDba436ds+VWB4E9kaL+D2wTuaiU+4Hx7DcyJ3leksXXM85koV/97f8Gn4nd3epxucaurcXmcEvU/VfqU7bKTdLdLwB7yPaZ45ilmBh/GqGJnmfq9csVuyZ0cwIDAQAB',
        "callbackUrl" => ''
    );


    public static $installmentMin = 3500000;
    public static $installmentEnable = true;

    public function __construct($env)
    {
        $this->env = $env;
        $this->alepayUtils = new AlepayUnit();

        if($env = true){
            $paymentProvider = Cache::get('provider_33');
            if ($paymentProvider == null) {
                $paymentProvider = PaymentProvider::findOne(33);
                Cache::set('provider_33', $paymentProvider, 60 * 60 * 24);
            }
            $this->merchant_pro['apiKey'] = $paymentProvider->email;
            $this->merchant_pro['encryptKey'] = $paymentProvider->secret_key;
            $this->merchant_pro['checksumKey'] = $paymentProvider->merchantVerifyId;
            //return $this->merchant_pro;
        }else{
            //return $this->merchant_dev;
        }


    }

    public function GetMerchantAlepay()
    {
        if($this->env == true){
            return $this->merchant_pro;
        }else{
            return $this->merchant_dev;
        }
    }


    /*
     * sendOrder - Send order information to Alepay service
     * @param array|null $data
     */
    public function CheckOut($data)
    {
        //todo get Url
        $url = $this->GetMerchantAlepay()['requestPayment'];
        //todo Send data endcode <=> decode
        $this->binCode = $data->orderCode;
        $result = $this->sendRequestToAlepay($data, $url);
        return $result;
    }

    public function CheckInfoPayment($data){
        //todo get Url
        $url = $this->GetMerchantAlepay()['getTransactionInfo'];
        $result = $this->sendRequestToAlepay($data, $url);
        return $result;
    }


    private function sendRequestToAlepay($data, $url)
    {
        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $publicKey = $this->GetMerchantAlepay()['encryptKey'];
        $dataEncrypt = $this->alepayUtils->encryptData(json_encode($data), $publicKey);


        $checksum = md5($dataEncrypt . $this->GetMerchantAlepay()['checksumKey']);
        $items = array(
            'token' => $this->GetMerchantAlepay()['apiKey'],
            'data' => $dataEncrypt,
            'checksum' => $checksum
        );

        $data_string = json_encode($items);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        $result =json_decode($result);
        $dataDecrypted = $result;
        $this->log = New PaymentLog();
        $this->log->status = 'fail-'.$result->errorCode;
        if($result->errorCode == 000){
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->GetMerchantAlepay()['encryptKey']);
            $dataDecrypted =json_decode($dataDecrypted);
            if(isset($dataDecrypted->status) && $dataDecrypted->status == '000' && $url == $this->GetMerchantAlepay()['getTransactionInfo']){
                $this->log->status = 'success';
            }
            if($url == $this->GetMerchantAlepay()['requestPayment']){
                $this->log->status = 'success';
            }
        }

        //todo log requset
        $log = PaymentLog::find()->where(['provider'=>'ALEPAY'])->andWhere(['bincode'=>$this->binCode])->andWhere(['action'=>$url])->andWhere(['status'=>$this->log->status])->one();
        $this->log->provider = 'ALEPAY';
        $this->log->bincode = $this->binCode;
        $this->log->action = $url;
        $this->log->request = $data;
        $this->log->request_encode = $items;
        $this->log->respone = $dataDecrypted;
        $this->log->respone_encode = $result;
        $this->log->time = date('Y-m-d H:i:s');
        if(!$log){
            $this->log->save(false);
        }
        return ($dataDecrypted);
    }


}