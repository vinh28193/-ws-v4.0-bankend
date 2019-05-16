<?php

namespace common\models\payment\vietnam;

use common\components\Cache;
use common\components\RedisQueue;
use common\components\ReponseData;
use common\components\UrlComponent;


use common\components\XmlSoapFormat;
use common\models\Order;

use common\models\model\PaymentGatewayRequests;
use common\models\model\PaymentProvider;

//use common\models\payment\model\NLCheckPaymentResponse;
//use common\models\payment\model\PaymentResponse;
//use common\models\payment\service\NLPaymentService;
//use common\models\wallet\Wallet;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use DOMDocument;

//use common\lib\Log4P;
//use weshop\modules\payment\models\Payment;
use SimpleXMLElement;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\form\ResponePaymentForm;
use wallet\modules\v1\models\WalletMerchant;
use wallet\modules\v1\models\WalletTransaction;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;

class NganLuong
{
    public static $methodPrefix = [
        'VPB_',
        'VCB_',
        'HDB_',
        'TCB_',
        'JCB_',
        'FEC_',
        'MSB_',
        'TPB_',
        'SCB_',
        'SGCB_'
    ];
    public static $checkBin = [
        'VCB_VISA',
        'VCB_MASTER',
        'VPB_VISA',
        'VPB_MASTER',
        'HDB_VISA',
        'HDB_MASTER',
        'TCB_VISA',
        'TCB_MASTER',
        'JCB_VISA',
        'JCB_MASTER',
        'FEC_MASTER',
        'MSB_VISA',
        'MSB_MASTER',
        'TPB_VISA',
        'TPB_MASTER',
        'SCB_VISA',
        'SCB_MASTER',
        'SGCB_VISA',
        'SGCB_MASTER'
    ];

    public $wallet_merchant_id = 1;
    public $token;
    public $page;
    public $binCode;

    /**
     * Tạo giao dịch với ngân lượng
     * @param Payment $request
     * @return PaymentResponse
     */
    public static function makePayment(Payment $request)
    {
        $param = [];
        if ($request->page == Payment::PAGE_ADDFEE) {
            $request->cancelUrl = \Yii::$app->store->getUrl() . UrlComponent::step3_addfee($request->addfee_bin);
            $param["order_code"] = $request->addfee_bin;
        } else {
            $request->cancelUrl = \Yii::$app->store->getUrl() . UrlComponent::step3_bill($request->order_bin);
            $param["order_code"] = $request->order_bin;
        }
        if ($request->page == Payment::PAGE_TOPUP) {
            $request->cancelUrl = \Yii::$app->store->getUrl() . UrlComponent::step3_bill($request->wallet_bin);
            $param["order_code"] = $request->wallet_bin;
        }
        $param["order_description"] = $request->order_note;
        $param["total_amount"] = round($request->total_amount, 0);
        $param["fee_shipping"] = 0;
        $param["payment_method"] = self::replaceMethod($request->paymentMethodName);
        if (strpos($request->bankCode, "VISA") !== false) {
            $param['bank_code'] = 'VISA';
        } else if (strpos($request->bankCode, "MASTER") !== false) {
            $param['bank_code'] = 'MASTER';
        } else if (strpos($request->bankCode, "QRCODE") !== false) {
            $param['bank_code'] = 'ICB';
        } else {
            $param["bank_code"] = $request->bankCode;
        }
//        $param["bank_code"] =  str_replace(['_VISA', '_MASTER'], '', $request->bankCode);
        $param["return_url"] = $request->returnUrl;
        $param["cancel_url"] = $request->cancelUrl;
        $param["buyer_fullname"] = $request->customer_name;
        $param["buyer_email"] = $request->customer_email;
        $param["buyer_mobile"] = str_replace(["+84", "+084"], "0", $request->customer_phone);
        $param["buyer_address"] = $request->customer_address;
        $param['function'] = "SetExpressCheckout";
        if (in_array($request->bankCode, self::$checkBin)) {
            $param['event_code'] = $param["bank_code"] . '_CHECK_BIN';
        } else {
            $param['event_code'] = "";
        }
        $param['event_discount'] = 0;
        $now = time();

        $param['merchant_id'] = Yii::$app->params['nganluong_pro']['ID'];
        $param['merchant_password'] = md5(\Yii::$app->params['nganluong_pro']['PASS']);
        $param["receiver_email"] = \Yii::$app->params['nganluong_pro']['ACC'];
        $param['version'] = "3.1";
        $param['time_limit'] = 1440;

        $email_test = \Yii::$app->params['EmailTest'];
        if (!\Yii::$app->params['environments_product'] || in_array($param["buyer_email"], $email_test)) {
            $request->config->submitUrl = str_replace("https://www.nganluong.vn", "https://sandbox.nganluong.vn:8088/nl30", $request->config->submitUrl);
            $param['merchant_id'] = \Yii::$app->params['nganluong']['sandbox']['ID'];
            $param['merchant_password'] = md5(\Yii::$app->params['nganluong']['sandbox']['PASS']);
            $param["receiver_email"] = \Yii::$app->params['nganluong']['sandbox']['ACC'];
        }

        if (!\Yii::$app->params['environments_product_wallet'] && $request->page == Payment::PAGE_TOPUP) {
            $request->config->submitUrl = \Yii::$app->params['nganluong']['sandbox_esc']['URL'];
            $param['merchant_id'] = \Yii::$app->params['nganluong']['sandbox_esc']['ID'];
            $param['merchant_password'] = md5(\Yii::$app->params['nganluong']['sandbox_esc']['PASS']);
            $param["receiver_email"] = \Yii::$app->params['nganluong']['sandbox_esc']['ACC'];
        }


        if (Yii::$app->params['environments_product_wallet'] && $request->page == Payment::PAGE_TOPUP) {
            $request->config->submitUrl = \Yii::$app->params['nganluong']['prod_esc']['URL'];
            $param['merchant_id'] = \Yii::$app->params['nganluong']['prod_esc']['ID'];
            $param['merchant_password'] = md5(\Yii::$app->params['nganluong']['prod_esc']['PASS']);
            $param["receiver_email"] = \Yii::$app->params['nganluong']['prod_esc']['ACC'];
            if(in_array($param["buyer_email"], $email_test)){
                $request->config->submitUrl = \Yii::$app->params['nganluong']['sandbox_esc']['URL'];
                $param['merchant_id'] = \Yii::$app->params['nganluong']['sandbox_esc']['ID'];
                $param['merchant_password'] = md5(\Yii::$app->params['nganluong']['sandbox_esc']['PASS']);
                $param["receiver_email"] = \Yii::$app->params['nganluong']['sandbox_esc']['ACC'];
            }
        }

        \Yii::info('NganLuong Params:' . json_encode($param), __METHOD__);
        $resp = self::call($request->config->submitUrl, $param);
        \Yii::info('NganLuong RES:' . json_encode($resp), __METHOD__);

        if ($resp == null || !is_array($resp) || !isset($resp['error_code']) || empty($resp['error_code']) || $resp['error_code'] != '00' || !isset($resp['token'])) {
            return new PaymentResponse(false, null, null, "Lỗi thanh toán trả về NL" . $resp['error_code'], "GET", $request);
        }
        if ($request->page != Payment::PAGE_ADDFEE && $request->page != Payment::PAGE_TOPUP) {
            $data_push['type'] = 'trunggianweshop';
            $data_push['token'] = $resp['token'];
            $data_push['time'] = date('Y-m-d H:i:s');
            $data_push['bin_code'] = $request->order_bin;
            $queue = new RedisQueue(\Yii::$app->params['TRANSACTION_QUEUE_ERROR']);
            $queue->push($data_push);
        }
        if ($request->page == Payment::PAGE_TOPUP) {
            $data_push['type'] = 'nganluongesc';
            $data_push['token'] = $resp['token'];
            $data_push['time'] = date('Y-m-d H:i:s');
            $data_push['wallet_bin'] = $request->wallet_bin;
            $queue = new RedisQueue(\Yii::$app->params['TRANSACTION_QUEUE_WALLET_NL']);
            $queue->push($data_push);
        }

        $user = $param['bank_code'] . '-' . $param["payment_method"];
        return new PaymentResponse(true, $resp['token'], isset($resp['checkout_url']) ? $resp['checkout_url'] : null, "Call nl thành công", "GET", $request);
    }

    /**
     * Check trạng thái giao dịch với ngân lượng
     * @param type $token
     * @return CheckPaymentResponse
     */
    public function checkPayment($saveLog = true)
    {
        $provider = Cache::get('provider_22');
        if ($provider == null) {
            $provider = PaymentProvider::findOne(22);
            Cache::set('provider_22', $provider, 60 * 60 * 24);
        }
        $params['merchant_id'] = $provider->merchantVerifyId;
        $params['merchant_password'] = md5($provider->secret_key);
        $params['version'] = "3.1";
        $params["receiver_email"] = $provider->email;
        $params['token'] = $this->token;
        $params['function'] = 'GetTransactionDetail';
        $params['merchant_id'] = \Yii::$app->params['WSVN-ESC-PROD']['ID'];
        $params['merchant_password'] = md5(\Yii::$app->params['WSVN-ESC-PROD']['PASS']);
        $params["receiver_email"] = \Yii::$app->params['WSVN-ESC-PROD']['ACC'];

        if ($this->wallet_merchant_id == WalletMerchant::WALEET_MERCHAN_ID_ESC_DEV) {
            $provider->submit_url = \Yii::$app->params['WSVN-ESC-SANDBOX']['URL']; //str_replace("https://www.nganluong.vn", "https://sandbox.nganluong.vn:8088/nl30", $provider->submit_url);
            $params['merchant_id'] = \Yii::$app->params['WSVN-ESC-SANDBOX']['ID'];
            $params['merchant_password'] = md5(\Yii::$app->params['WSVN-ESC-SANDBOX']['PASS']);
            $params["receiver_email"] = \Yii::$app->params['WSVN-ESC-SANDBOX']['ACC'];
        }

        if(in_array($this->page, [Payment::PAGE_ADDFEE,Payment::PAGE_CHECKOUT,Payment::PAGE_ADDFEE_NEW,Payment::PAGE_BILL])){
            $order = Order::GetbyBinCode($this->binCode);
            //todo check transaction Shop
            //todo product
            $param['merchant_id'] = \Yii::$app->params['nganluong']['prod_trunggian']['ID'];
            $param['merchant_password'] = md5(\Yii::$app->params['nganluong']['prod_trunggian']['PASS']);
            $param["receiver_email"] = \Yii::$app->params['nganluong']['prod_trunggian']['ACC'];

            if(Yii::$app->params['environments_product']==false){
                //todo env dev
                $provider->submit_url = Yii::$app->params['nganluong']['sandbox']['URL'];
                $params['merchant_id'] = Yii::$app->params['nganluong']['sandbox']['ID'];
                $params['merchant_password'] = md5(\Yii::$app->params['nganluong']['sandbox']['PASS']);
                $params["receiver_email"] = Yii::$app->params['nganluong']['sandbox']['ACC'];
            }
        }

        \Yii::info('params:'.json_encode($params),'NganLuong::checkPayment');
        \Yii::info(\Yii::$app->params['WSVN-ESC-SANDBOX']['URL'],'NganLuong::submit_url');
        $resp = self::call($provider->submit_url, $params);
        if (!empty($resp['order_code'])) {
            $order_code = explode('-', $resp['order_code']);
            $order_code = $resp['order_code'];
        }

        //todo update order payment
        if(in_array($this->page, [Payment::PAGE_ADDFEE,Payment::PAGE_CHECKOUT,Payment::PAGE_ADDFEE_NEW,Payment::PAGE_BILL])){
            if($resp['error_code'] == 00){
                Order::updatePaymentSuccess($resp['order_code']);
            }
        }
        //log
        if ($saveLog) {
            $log = new PaymentGatewayRequests();

            $log->binCode = !empty($order_code) ? $order_code : '1';
            $log->requestId = $this->token;
            $log->requestType = 'CALLBACK';
            $log->requestUrl = $provider->submit_url;
            $log->paymentGateway = !empty($resp['payment_method']) ? $resp['payment_method'] : '';
            $log->paymentBank = !empty($resp['bank_code']) ? $resp['bank_code'] : '';
            $log->amount = !empty($resp['total_amount']) ? $resp['total_amount'] : '';
            $log->responseContent = json_encode($resp);
            $log->requestContent = json_encode($params);
            $log->responseTime = date('Y-m-d H:i:s');
            $log->createTime = date('Y-m-d H:i:s');
            $log->storeId = 1; //vn
            $log->save(false);

        }
        $request_content['api'] = $provider->submit_url;
        $request_content['form'] = $params;
        $datars['request_content'] = $request_content;
        $datars['response_content'] = $resp;
        if ($resp['error_code'] == 00 && $resp['transaction_status'] == 00) {
            return ReponseData::reponseMess(true,'Check payment thành công',$datars) ;
        } else {
            return ReponseData::reponseMess(false,'Check payment không thành công', $datars);
        }
    }

    /**
     * call nl service
     * @param type $function
     * @param type $params
     * @return type
     */
    public function call($submitURL, $params = [])
    {
        $ch = curl_init($submitURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        curl_setopt($ch, CURLOPT_POSTFIELDS, self::buildParams($params));
        $result = curl_exec($ch);
//        $message = [
//            'action' => $submitURL,
//            'request' => json_encode($params),
//            'response' => json_encode($result),
//        ];
//        $action = str_replace('/', '-', str_replace("https://www.nganluong.vn", "", str_replace("https://sandbox.nganluong.vn:8088/nl30", "", $submitURL)));
//        try {
//            Log4P::writeToFirebase('frontend-logs-v3/third-party-logs/partner/store/1/ngan-luong/action/' . $action . '/year/' . date('Y') . '/month/' . date('m') . '/day/' . date('d') . '/hour/' . date('H'), $message);
//        } catch (\Exception $e) {
//            throw $e;
//        }
        curl_close($ch);
        return self::xmlToArray($result);
    }

    /**
     * call nl service
     * @param type $function
     * @param type $params
     * @return type
     */
    public static function callApiNL($submitURL, $params = [])
    {
        $query = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['nganluong']['URL']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        $result = curl_exec($ch);
        curl_close($ch);
        if (!empty($result)) {
            $result = \GuzzleHttp\json_decode($result);
        }
        return $result;
    }

    private static function buildParams($params)
    {
        $query = "";
        $i = 1;
        foreach ($params as $key => $val) {
            $query .= ($i == 1 ? "" : "&") . $key . "=" . $val;
            $i++;
        }
        return $query;
    }

    public static function xmlToArray($xmlstr)
    {
        $xmlstr = trim(preg_replace('/(.*)(\s*)(<\?xml)/', '$3', $xmlstr));
        preg_match('/<checkout_url>(.*)<\/checkout_url>/', $xmlstr, $out);
        if (isset($out[0])) {
            $xmlstr = str_replace($out[0], '', $xmlstr);
        }
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        $arrResult = self::domToArray($doc->documentElement);
        if (isset($out[1])) {
            $arrResult = array_merge($arrResult, array('checkout_url' => $out[1]));
        }
        return $arrResult;
    }

    private static function domToArray($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                break;
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::domToArray($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t]))
                            $output[$t] = array();
                        $output[$t][] = $v;
                    } elseif ($v) {
                        $output = (string)$v;
                    }
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode)
                            $a[$attrName] = (string)$attrNode->value;
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes')
                            $output[$t] = $v[0];
                    }
                }
                break;
        }
        return $output;
    }

    public static function replaceMethod($method)
    {
        return str_replace(self::$methodPrefix, '', str_replace('MASTER', 'VISA', $method));
    }


    public static function checkTransactionPaymentGateway($transactionId)
    {
        $merchant_NL = \Yii::$app->params['DEV-NGANLUONG'];

        $transaction = WalletTransaction::findOne($transactionId);
        if($transaction){
            $timeto = $transaction->update_at ? $transaction->update_at : $transaction->create_at;
            $data_request_arr = [
                'function' => 'getTransaction',
                'filter' => [
                    'merchant_id' => $merchant_NL['ID'],
                    'merchant_password' => md5($merchant_NL['PASS']),
                    'email' => $merchant_NL['ACC'],
                    'time_created_from' => '1524322800',
                    'time_created_to' => '1526902200',
                    'type_filter' => '',
                    'value_filter' => '',
                    'type' => '',
                    'status' => '',
                    'page' => '',
                    'size' => '',
                ]
            ];
            $client = new Client([
                'baseUrl' => 'https://sandbox.nganluong.vn:8088',
                'formatters' => [
                    'xmlSoap' => 'common\components\XmlSoapFormat', // add new formatter
                ],
            ]);

            $response = $client->createRequest()
                ->setHeaders(['Content-type' => 'text/xml'])
                ->setFormat('xmlSoap')
                ->setMethod('POST')
                ->setUrl('nl30/payoutTranfer.php')
                ->setData($data_request_arr)
                ->send();

            $array = XmlSoapFormat::parser($response);
            if ($array['result']['response_code'] === 'E00') {

            }
        }
    }

    // param $data = [
    //      'receiver_email' => 'test@test.com',
    //      'amount' => 10000,
    //      'reference_code' => 123456, // mã đơn hàng, mã transaction . có thể tự gen ra, nhưng phải là duy nhất
    //]
    public static function tranferWalletNL($data)
    {
        if (!empty($data) && !empty($data['receiver_email']) && !empty($data['amount']) && !empty($data['reference_code']))
            $merchant_NL = \Yii::$app->params['DEV-NGANLUONG'];
        $data_request_arr = [
            'function' => 'tranfer',
            'filter' => [
                'merchant_id' => $merchant_NL['ID'],
                'merchant_password' => $merchant_NL['PASS'],
                'email' => $merchant_NL['ACC'],
                'receiver_email' => $data['receiver_email'],
                'amount' => $data['amount'],
                'reference_code' => $data['reference_code'],
            ]
        ];
        $client = new Client([
            'baseUrl' => 'https://sandbox.nganluong.vn:8088',
            'formatters' => [
                'xmlSoap' => 'common\components\XmlSoapFormat', // add new formatter
            ],
        ]);

        $response = $client->createRequest()
            ->setHeaders(['Content-type' => 'text/xml'])
            ->setFormat('xmlSoap')
            ->setMethod('POST')
            ->setUrl('nl30/payoutTranfer.php')
            ->setData($data_request_arr)
            ->send();
        $array = XmlSoapFormat::parser($response);
        if ($array['result']['response_code'] === 'E00') {
            // Todo Api chuyen tien tu vi sang vi
        }

    }

    public static function withDrawNL()
    {

        $datacode = [
            'rand' => rand(111111, 1000000),
            'amount' => '1100000',
            'bank_code' => 'BAB',
            'time' => time(),
        ];
        $data = [
            'merchant_id' => '30439',
            'merchant_password' => '034cdf00b48ea2ba265880b9e357f62b',
            'receiver_email' => 'nguyencamhue@gmail.com',
            'func' => 'SetCashoutRequest',
            'ref_code' => self::genRefCodeWithDraw($datacode),
            'total_amount' => '1100000',
            'bank_code' => 'BAB',
            'card_fullname' => 'Nguyen Hue',
            'card_number' => '9874563254178962'
        ];
        $client = new Client([
            'baseUrl' => 'https://sandbox.nganluong.vn:8088',
            'formatters' => [
                'xmlSoap' => 'common\components\XmlSoapFormat', // add new formatter
            ],
        ]);

        $response = $client->createRequest()
            ->setHeaders(['Content-type' => 'text/xml'])
            ->setFormat('xmlSoap')
            ->setMethod('POST')
            ->setUrl('nl35/withdraw.api.post.php')
            ->setData($data)
            ->send();
        print_r(json_decode($response->content, true));
    }

    protected static function genRefCodeWithDraw($param = [])
    {
        $code = base64_encode(json_encode($param));
        return sha1($code);
    }
}
