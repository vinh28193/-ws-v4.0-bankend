<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/3/2018
 * Time: 9:58 AM
 */

namespace common\models\payment\indonesia;


use common\components\UrlComponent;
use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\model_logs\PaymentLog;
use common\models\payment\indonesia\nicepay\InstalmentConfig;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use yii\helpers\Url;

class Nicepay
{

    const NICEPAY_IMID = 'WESHOP1122';
    const NICEPAY_MERCHANT_KEY = 'vnMmWGv8+Ao7P9iI3G9IdwQ1cefHOrryIa4ELPBTd/uTCXdW4R+vTfABNuM6ofeiokxG976f9Mh9YywR7WLEJQ==';
    const NICEPAY_IMID_INSTALLMENT = 'WESHOPINS2';
    const NICEPAY_MERCHANT_KEY_INSTALLMENT = 'p9tK0wDh/sodB9caI0eN/ZNNjgPw8qwBykqR7rlO/GAAxlLMY5EUbTvon6j83Iwwa5DDefC0V+kj//cS5Hikjw==';
    const NICEPAY_CALLBACK_URL = 'http://weshop.co.id/nicepay-payment.html';
    const NICEPAY_DBPROCESS_URL = 'http://weshop.co.id/nicepay-payment.html';

    const NICEPAY_TIMEOUT_CONNECT = 15;
    const NICEPAY_TIMEOUT_READ = 25;

    const NICEPAY_PROGRAM = 'NicepayLite';
    const NICEPAY_VERSION = '1.11';
    const NICEPAY_BUILDDATE = '20160309';
    const NICEPAY_REQ_CC_URL = 'https://www.nicepay.co.id/nicepay/api/orderRegist.do';
    const NICEPAY_REQ_VA_URL = 'https://www.nicepay.co.id/nicepay/api/onePass.do';
    const NICEPAY_CANCEL_VA_URL = 'https://www.nicepay.co.id/nicepay/api/onePassAllCancel.do';
    const NICEPAY_ORDER_STATUS_URL = 'https://www.nicepay.co.id/nicepay/api/onePassStatus.do';
    const NICEPAY_READ_TIMEOUT_ERR = '10200';


    public $isInstallment =false;
    public $tXid;
    public $authNo;
    public $bankVacctNo;
    public $resultCd;
    public $resultMsg;
    public $iMid = self::NICEPAY_IMID;
    public $iMidInstallment = self::NICEPAY_IMID_INSTALLMENT;
    public $callBackUrl;
    public $dbProcessUrl;
    public $merchantKey = self::NICEPAY_MERCHANT_KEY;
    public $merchantKeyInstallment = self::NICEPAY_MERCHANT_KEY_INSTALLMENT;
    public $cartData;
    public $requestData = array();
    public $resultData = array();
    public $debug;
    public $request;

    public $payment;

    public function __construct(Payment $payment = null)
    {
        $this->payment = $payment ? $payment : $this->payment;
        $this->request = new NicepayRequestor();
    }

    public function getUserIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
    }

    public function oneLiner($string)
    {
        // Return string in one line, remove new lines and white spaces
        return preg_replace(array('/\n/', '/\n\r/', '/\r\n/', '/\r/', '/\s+/', '/\s\s*/'), ' ', $string);
    }

    public function extractNotification($name)
    {
        if (is_array($name)) {
            foreach ($name as $value) {
                if (isset($_REQUEST[$value])) {
                    $this->notification[$value] = $_REQUEST[$value];
                } else {
                    $this->notification[$value] = null;
                }
            }
        } elseif (isset($_REQUEST[$name])) {
            $this->notification[$name] = $_REQUEST[$name];
        } else {
            $this->notification[$name] = null;
        }
    }

    public function getNotification($name)
    {
        return $this->notification[$name];
    }

    public function merchantToken()
    {
        if($this->isInstallment){
            return hash('sha256', $this->iMidInstallment .
                $this->get('tXid') .
                $this->get('amt') .
                $this->merchantKeyInstallment
            );
        }
        // SHA256( Concatenate(iMid + referenceNo + amt + merchantKey) )
        return hash('sha256', $this->get('iMid') .
            $this->get('referenceNo') .
            $this->get('amt') .
            $this->merchantKey
        );
    }

    public function merchantTokenInstallment()
    {
        // SHA256( Concatenate(iMid + referenceNo + amt + merchantKey) )
        return hash('sha256', $this->get('iMid') .
            $this->get('referenceNo') .
            $this->get('amt') .
            $this->merchantKeyInstallment
        );
    }

    public function merchantTokenC()
    {
        // SHA256( Concatenate(iMid + referenceNo + amt + merchantKey) )
        return hash('sha256', $this->get('iMid') .
            $this->get('tXid') .
            $this->get('amt') .
            $this->merchantKey
        );
    }

    // Set POST parameter name and its value
    public function set($name, $value)
    {
        $this->requestData[$name] = $value;
    }

    // Retrieve POST parameter value
    public function get($name)
    {
        if (isset($this->requestData[$name])) {
            return $this->requestData[$name];
        }
        return "";
    }

    // Request VA
    public function requestVA()
    {
        // Populate data
        $this->set('iMid', $this->iMid);
        $this->set('merchantToken', $this->merchantToken());
        $this->set('dbProcessUrl', $this->dbProcessUrl);
        $this->set('callBackUrl', $this->callBackUrl);
        $this->set('instmntMon', '1');
        $this->set('instmntType', '1');
        $this->set('userIP', $this->getUserIP());
        $this->set('goodsNm', $this->get('description'));
        $this->set('vat', '0');
        $this->set('fee', '0');
        $this->set('notaxAmt', '0');
        if ($this->get('cartData') == "") {
            $this->set('cartData', '{}');
        }
//        $this->set('cartData', '{}');
        // Check Parameter
        $this->checkParam('iMid', '01');
        $this->checkParam('payMethod', '02');
        $this->checkParam('currency', '03');
        $this->checkParam('amt', '02');
        $this->checkParam('instmntMon', '05');
        $this->checkParam('referenceNo', '06');
        $this->checkParam('goodsNm', '07');
        $this->checkParam('billingNm', '08');
        $this->checkParam('billingPhone', '09');
        $this->checkParam('billingEmail', '10');
        $this->checkParam('billingAddr', '11');
        $this->checkParam('billingCity', '12');
        $this->checkParam('billingState', '13');
        $this->checkParam('billingCountry', '14');
        $this->checkParam('deliveryNm', '15');
        $this->checkParam('deliveryPhone', '16');
        $this->checkParam('deliveryAddr', '17');
        $this->checkParam('deliveryCity', '18');
        $this->checkParam('deliveryState', '19');
        $this->checkParam('deliveryPostCd', '20');
        $this->checkParam('deliveryCountry', '21');
        $this->checkParam('callBackUrl', '22');
        $this->checkParam('dbProcessUrl', '23');
        $this->checkParam('vat', '24');
        $this->checkParam('fee', '25');
        $this->checkParam('notaxAmt', '26');
        $this->checkParam('description', '27');
        $this->checkParam('merchantToken', '28');
        $this->checkParam('bankCd', '29');
        // Send Request
        $this->request->operation('requestVA');
        $this->request->openSocket();
        $this->resultData = $this->request->apiRequest($this->requestData);
        unset($this->requestData);
        return $this->resultData;
    }

    // Charge Credit Card
    public function chargeCard()
    {
        // Populate data
        $this->set('iMid', $this->iMidInstallment);
        $this->set('merchantToken', $this->merchantTokenInstallment());
        $this->set('dbProcessUrl', $this->dbProcessUrl);
        $this->set('callBackUrl', $this->callBackUrl);
        // $this->set('instmntMon', '1');
        // $this->set('instmntType', '0');
        $this->set('userIP', $this->getUserIP());
        $this->set('goodsNm', $this->get('description'));
        // $this->set('vat', '0');
        // $this->set('fee', '0');
        $this->set('notaxAmt', '0');
        if ($this->get('fee') == "") {
            $this->set('fee', '0');
        }
        if ($this->get('vat') == "") {
            $this->set('vat', '0');
        }
        if ($this->get('cartData') == "") {
            $this->set('cartData', '{}');
        }

//        $this->set('cartData', '{}');
//        var_dump($this->requestData);
//        exit();
        // Check Parameter
        $this->checkParam('iMid', '01');
        $this->checkParam('payMethod', '01');
        $this->checkParam('currency', '03');
        $this->checkParam('amt', '02');
        $this->checkParam('instmntMon', '05');
        $this->checkParam('referenceNo', '06');
        $this->checkParam('goodsNm', '07');
        $this->checkParam('billingNm', '08');
        $this->checkParam('billingPhone', '09');
        $this->checkParam('billingEmail', '10');
        $this->checkParam('billingAddr', '11');
        $this->checkParam('billingCity', '12');
        $this->checkParam('billingState', '13');
        $this->checkParam('billingCountry', '14');
        $this->checkParam('deliveryNm', '15');
        $this->checkParam('deliveryPhone', '16');
        $this->checkParam('deliveryAddr', '17');
        $this->checkParam('deliveryCity', '18');
        $this->checkParam('deliveryState', '19');
        $this->checkParam('deliveryPostCd', '20');
        $this->checkParam('deliveryCountry', '21');
        $this->checkParam('callBackUrl', '22');
        $this->checkParam('dbProcessUrl', '23');
        $this->checkParam('vat', '24');
        $this->checkParam('fee', '25');
        $this->checkParam('notaxAmt', '26');
        $this->checkParam('description', '27');
        $this->checkParam('merchantToken', '28');

//        print_r($this->requestData);
//        exit();
//       Send Request
        $this->request->operation('creditCard');
        $this->request->openSocket();
        $this->resultData = $this->request->apiRequest($this->requestData);
        unset($this->requestData);
        return $this->resultData;
    }

    public function checkPaymentStatus($tXid, $referenceNo, $amt)
    {
        // Populate data
        if($this->isInstallment){
            $this->set('iMid', $this->iMidInstallment);
        }else{
            $this->set('iMid', $this->iMid);
        }
        $this->set('tXid', $tXid);
        $this->set('referenceNo', $referenceNo);
        $this->set('amt', $amt);
        $this->set('merchantToken', $this->merchantToken());

        // Check Parameter
        $this->checkParam('iMid', '01');
        $this->checkParam('amt', '04');
        $this->checkParam('referenceNo', '06');
        $this->checkParam('merchantToken', '28');
        $this->checkParam('tXid', '36');

        // Send Request
        $this->request->operation('checkPaymentStatus');
        $this->request->openSocket();
        $this->resultData = $this->request->apiRequest($this->requestData);
        unset($this->requestData);
        return $this->resultData;
    }

    // Cancel VA (VA can be canceled only if VA status is not paid)
    public function cancelVA($tXid, $amt)
    {
        // Populate data
        $this->set('iMid', $this->iMid);
        $this->set('merchantToken', $this->merchantTokenC());
        $this->set('tXid', $tXid);
        $this->set('amt', $amt);

        // Check Parameter
        $this->checkParam('iMid', '01');
        $this->checkParam('amt', '04');
        $this->checkParam('merchantToken', '28');
        $this->checkParam('tXid', '36');

        // Send Request
        $this->request->operation('cancelVA');
        $this->request->openSocket();
        $this->resultData = $this->request->apiRequest($this->requestData);
        unset($this->requestData);
        return $this->resultData;
    }

    public function checkParam($requestData, $errorNo)
    {
        if (null === $this->get($requestData)) {
            die($this->getErrorCode($errorNo));
        }
    }

    public function getErrorCode($id)
    {
        $error = array(
            // That always Unknown Error :)
            '00' => array(
                'errorCode' => '00000',
                'errorMsg' => 'Unknown error. Contact it.support@ionpay.net.'
            ),
            // General Mandatory parameters
            '01' => array(
                'error' => '10001',
                'errorMsg' => '(iMid) is not set. Please set (iMid).'
            ),
            '02' => array(
                'error' => '10002',
                'errorMsg' => '(payMethod) is not set. Please set (payMethod).'
            ),
            '03' => array(
                'error' => '10003',
                'errorMsg' => '(currency) is not set. Please set (currency).'
            ),
            '04' => array(
                'error' => '10004',
                'errorMsg' => '(amt) is not set. Please set (amt).'
            ),
            '05' => array(
                'error' => '10005',
                'errorMsg' => '(instmntMon) is not set. Please set (instmntMon).'
            ),
            '06' => array(
                'error' => '10006',
                'errorMsg' => '(referenceNo) is not set. Please set (referenceNo).'
            ),
            '07' => array(
                'error' => '10007',
                'errorMsg' => '(goodsNm) is not set. Please set (goodsNm).'
            ),
            '08' => array(
                'error' => '10008',
                'errorMsg' => '(billingNm) is not set. Please set (billingNm).'
            ),
            '09' => array(
                'error' => '10009',
                'errorMsg' => '(billingPhone) is not set. Please set (billingPhone).'
            ),
            '10' => array(
                'error' => '10010',
                'errorMsg' => '(billingEmail) is not set. Please set (billingEmail).'
            ),
            '11' => array(
                'error' => '10011',
                'errorMsg' => '(billingAddr) is not set. Please set (billingAddr).'
            ),
            '12' => array(
                'error' => '10012',
                'errorMsg' => '(billingCity) is not set. Please set (billingCity).'
            ),
            '13' => array(
                'error' => '10013',
                'errorMsg' => '(billingState) is not set. Please set (billingState).'
            ),
            '14' => array(
                'error' => '10014',
                'errorMsg' => '(billingCountry) is not set. Please set (billingCountry).'
            ),
            '15' => array(
                'error' => '10015',
                'errorMsg' => '(deliveryNm) is not set. Please set (deliveryNm).'
            ),
            '16' => array(
                'error' => '10016',
                'errorMsg' => '(deliveryPhone) is not set. Please set (deliveryPhone).'
            ),
            '17' => array(
                'error' => '10017',
                'errorMsg' => '(deliveryAddr) is not set. Please set (deliveryAddr).'
            ),
            '18' => array(
                'error' => '10018',
                'errorMsg' => '(deliveryCity) is not set. Please set (deliveryCity).'
            ),
            '19' => array(
                'error' => '10019',
                'errorMsg' => '(deliveryState) is not set. Please set (deliveryState).'
            ),
            '21' => array(
                'error' => '10020',
                'errorMsg' => '(deliveryPostCd) is not set. Please set (deliveryPostCd).'
            ),
            '22' => array(
                'error' => '10021',
                'errorMsg' => '(deliveryCountry) is not set. Please set (deliveryCountry).'
            ),
            '23' => array(
                'error' => '10022',
                'errorMsg' => '(callBackUrl) is not set. Please set (callBackUrl).'
            ), '8' => array(
                'error' => '10023',
                'errorMsg' => '(dbProcessUrl) is not set. Please set (dbProcessUrl).'
            ),
            '24' => array(
                'error' => '10024',
                'errorMsg' => '(vat) is not set. Please set (vat).'
            ),
            '25' => array(
                'error' => '10025',
                'errorMsg' => '(fee) is not set. Please set (fee).'
            ),
            '26' => array(
                'error' => '10026',
                'errorMsg' => '(notaxAmt) is not set. Please set (notaxAmt).'
            ),
            '27' => array(
                'error' => '10027',
                'errorMsg' => '(description) is not set. Please set (description).'
            ),
            '28' => array(
                'error' => '10028',
                'errorMsg' => '(merchantToken) is not set. Please set (merchantToken).'
            ),
            '29' => array(
                'error' => '10029',
                'errorMsg' => '(bankCd) is not set. Please set (bankCd).'
            ),
            // Mandatory parameters to Check Order Status
            '30' => array(
                'error' => '10030',
                'errorMsg' => '(tXid) is not set. Please set (tXid).'
            )
        );
        return (json_encode($this->oneLiner($error[$id])));
    }

    public function createPayment()
    {
        $bankCd = !empty($this->payment->bankCode) ? $this->payment->bankCode : '';
        $dateNow = date('Ymd');
        $vaExpiryDate = date('Ymd', strtotime($dateNow . ' +1 day')); // Set VA expiry date +1 day (optional)
        //      // Populate Mandatory parameters to send
        $this->set('currency', 'IDR');
        $this->set('amt', round($this->payment->total_amount)); // Total gross amount
        $this->dbProcessUrl = $this->payment->returnUrl;
        if ($this->payment->page == Payment::PAGE_ADDFEE) {
            $this->set('referenceNo', $this->payment->addfee_bin); // Invoice Number or Referenc Number Generated by merchant
            $this->callBackUrl = $this->payment->returnUrl;
        } else {
            $this->set('referenceNo', $this->payment->order_bin); // Invoice Number or Referenc Number Generated by merchant
            $this->callBackUrl = $this->payment->returnUrl;
        }
        $this->set('description', 'Payment of Invoice No ' . $this->get('referenceNo')); // Transaction description
        $this->set('billingNm', $this->payment->customer_name); // Customer name
        $this->set('billingPhone', $this->payment->customer_phone); // Customer phone number
        $this->set('billingEmail', $this->payment->customer_email); //
        $this->set('billingAddr', $this->payment->customer_address);
        $this->set('billingCity', $this->payment->customer_district);
        $this->set('billingState', $this->payment->customer_city);
        $this->set('billingPostCd', $this->payment->customer_postcode);
        $this->set('billingCountry', $this->payment->customer_country);

        $this->set('deliveryNm', $this->payment->customer_name); // Delivery name
        $this->set('deliveryPhone', $this->payment->customer_phone);
        $this->set('deliveryEmail', $this->payment->customer_email);
        $this->set('deliveryAddr', $this->payment->customer_address);
        $this->set('deliveryCity', $this->payment->customer_district);
        $this->set('deliveryState', $this->payment->customer_city);
        $this->set('deliveryPostCd', $this->payment->customer_postcode);
        $this->set('deliveryCountry', $this->payment->customer_country);

        if ($this->payment->paymentMethod == InstalmentConfig::PAYMENT_METHOD && $this->payment->total_amount < InstalmentConfig::AMOUNT_REQUIRED) {
            return new Response(false, 'Đơn hàng không đủ điều kiện thanh toán trả góp', null);
        }
        if ($this->payment->instalment_type == 2 && !empty($this->payment->installment_bank) && !empty($this->payment->installment_method)) {
            $this->set('payMethod', '01');
            $this->set('instmntMon', $this->payment->installment_month);
            $this->set('instmntType', $this->payment->instalment_type);
            $response = $this->chargeCard();
            if (isset($response->data->resultCd) && $response->data->resultCd == '0000') {
                $checkoutUrl = $response->data->requestURL . '?tXid=' . $response->data->tXid;
                $rs = new PaymentResponse(true, null, $checkoutUrl, 'Success', "GET", $this->payment);
                return $rs;
            } else {
                return new PaymentResponse(false, 'Fail', $response);
            }
        } elseif ($this->payment->instalment_type == 1) {
            $this->set('payMethod', '02');
            $this->set('bankCd', $bankCd);
            $this->set('vacctValidDt', $vaExpiryDate); // Set VA expiry date example: +1 day
            $this->set('vacctValidTm', date('His')); // Set VA Expiry Time
            $response = $this->requestVA();
            if (isset($response->resultCd) && $response->resultCd == '0000') {
                return new Response(true, Yii::t('frontend', "Payment order"), $response);
            } else {
                return new Response(false, Yii::t('frontend', "Gagal melakukan transaksi pembayaran, silahkan mengisi informasi Anda dan coba lagi"));
            }
        } else {
            $this->set('payMethod', '02');
            $this->set('bankCd', $bankCd);
            $this->set('vacctValidDt', $vaExpiryDate); // Set VA expiry date example: +1 day
            $this->set('vacctValidTm', date('His')); // Set VA Expiry Time
            $response = $this->requestVA();

            if (isset($response->resultCd) && $response->resultCd == '0000') {
                $checkoutUrl = $this->payment->website->getUrl(). '/nicepay-' . ($this->payment->page==Payment::PAGE_ADDFEE?$this->payment->addfee_bin:$this->payment->order_bin) . '/success.html?bankVacctNo=' . $response->bankVacctNo . '&transTm=' . $response->transTm . '&transDt=' . $response->transDt;
                //return new Response(true, Yii::t('payment-note-success', "Thanh toánh thành công"), $response);
                return new PaymentResponse(true, null, $checkoutUrl, 'Nicepay banktranfer success', "GET", $this->payment);
            } else {
                //return new Response(false, 'Lỗi thanh toán', $response);
                return new PaymentResponse(false, null, null, 'Payment gate error'+ $response->resultCd, "GET", $this->payment);
            }
        }
    }

    public function checkPayment()
    {
        $pushParameters = array('tXid',
            'referenceNo',
            'merchantToken',
            'amt'
        );

        $this->extractNotification($pushParameters);

        $iMid = $this->iMid;
        $tXid = $this->getNotification('tXid');
        $referenceNo = $this->getNotification('referenceNo');
        $amt = $this->getNotification('amt');

        if (empty($referenceNo)) {
            return false;
        }
        $thisData = array($iMid, $tXid, $referenceNo, $amt);
        //log
        $log = new PaymentGatewayRequests();
        $log->binCode = $referenceNo;
        $log->requestType = 'CALLBACK';
        $log->requestUrl = 'weshop/order/nicepaysuccess';
        $log->paymentGateway = 'NICEPAY';
        $log->requestContent = json_encode($thisData);
        $log->responseTime = date('Y-m-d H:i:s');
        $log->storeId = 7; //WSID

        $this->set('tXid', $tXid);
        $this->set('referenceNo', $referenceNo);
        $this->set('amt', $amt);
        $this->set('iMid', $iMid);
        $merchantToken = $this->merchantTokenC();
        $this->set('merchantToken', $merchantToken);
        $paymentStatus = $this->checkPaymentStatus($tXid, $referenceNo, $amt);
        $log->responseContent = json_encode($paymentStatus);
        $log->save(false);
        if (isset($paymentStatus->status) && $paymentStatus->status == 0) {
            Order::updatePaymentSuccess($referenceNo);
        }else{
            $this->isInstallment = true;
            $iMid = $this->iMidInstallment;
            $thisData = array($iMid, $tXid, $referenceNo, $amt);
            $this->set('tXid', $tXid);
            $this->set('referenceNo', $referenceNo);
            $this->set('amt', $amt);
            $this->set('iMid', $iMid);
            $log->requestContent =  $log->requestContent ." , ". json_encode($thisData);
            $merchantToken = $this->merchantTokenC();
            $this->set('merchantToken', $merchantToken);
            $paymentStatus = $this->checkPaymentStatus($tXid, $referenceNo, $amt);
            $log->responseContent =  $log->responseContent ." , ". json_encode($paymentStatus);
            $log->save(false);
            if($paymentStatus->resultMsg == 'paid' && $paymentStatus->resultCd == '0000'){
                Order::updatePaymentSuccess($referenceNo);
            }
        }
        return true;
    }

    public function getStatusCode($code)
    {
        // TODO: Implement getStatusCode() method.
    }

    public function getSign()
    {
        // TODO: Implement getSign() method.
    }
}

class NicepayRequestor
{
    public $sock = 0;
    public $apiUrl;
    public $port = 443;
    public $status;
    public $headers = "";
    public $body = "";
    public $request;
    public $errorcode;
    public $errormsg;
    public $timeout;

    public function __construct()
    {
        //$this->log = new NicepayLogger();
    }

    public function operation($type)
    {
        if ($type == "requestVA") {
            $this->apiUrl = Nicepay::NICEPAY_REQ_VA_URL;
        } else if ($type == "creditCard") {
            $this->apiUrl = Nicepay::NICEPAY_REQ_CC_URL;
        } else if ($type == "checkPaymentStatus") {
            $this->apiUrl = Nicepay::NICEPAY_ORDER_STATUS_URL;
        } else if ($type == "cancelVA") {
            $this->apiUrl = Nicepay::NICEPAY_CANCEL_VA_URL;
        }
        return $this->apiUrl;
    }

    public function openSocket()
    {
        $host = parse_url($this->apiUrl, PHP_URL_HOST);
        $tryCount = 0;
        if (!$this->sock = @fsockopen("ssl://" . $host, $this->port, $errno, $errstr, Nicepay::NICEPAY_TIMEOUT_CONNECT)) {
            while ($tryCount < 5) {
                if ($this->sock = @fsockopen("ssl://" . $host, $this->port, $errno, $errstr, Nicepay::NICEPAY_TIMEOUT_CONNECT)) {
                    return true;
                }
                sleep(2);
                $tryCount++;
            }
            $this->errorcode = $errno;
            switch ($errno) {
                case -3 :
                    $this->errormsg = 'Socket creation failed (-3)';
                case -4 :
                    $this->errormsg = 'DNS lookup failure (-4)';
                case -5 :
                    $this->errormsg = 'Connection refused or timed out (-5)';
                default :
                    $this->errormsg = 'Connection failed (' . $errno . ')';
                    $this->errormsg .= ' ' . $errstr;
            }
            return false;
        }
        return true;
    }

    public function apiRequest($data)
    {
        $host = parse_url($this->apiUrl, PHP_URL_HOST); // www.nicepay.co.id
        $uri = parse_url($this->apiUrl, PHP_URL_PATH); // /nicepay/api/onePass.do
        $this->headers = "";
        $this->body = "";
        $postdata = $this->buildQueryString($data);

        /* Write */
        $request = "POST " . $uri . " HTTP/1.0\r\n";
        $request .= "Connection: close\r\n";
        $request .= "Host: " . $host . "\r\n";
        $request .= "Content-type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-length: " . strlen($postdata) . "\r\n";
        $request .= "Accept: */*\r\n";
        $request .= "\r\n";
        $request .= $postdata . "\r\n";
        $request .= "\r\n";

        if ($this->sock) {
            fwrite($this->sock, $request);

            /* Read */
            stream_set_blocking($this->sock, FALSE);

            $atStart = true;
            $IsHeader = true;
            $timeout = false;
            $start_time = time();
            while (!feof($this->sock) && !$timeout) {
                $line = fgets($this->sock, 4096);
                $diff = time() - $start_time;
                if ($diff >= Nicepay::NICEPAY_TIMEOUT_READ) {
                    $timeout = true;
                }
                if ($IsHeader) {
                    if ($line == "") // for stream_set_blocking
                    {
                        continue;
                    }
                    if (substr($line, 0, 2) == "\r\n") // end of header
                    {
                        $IsHeader = false;
                        continue;
                    }
                    $this->headers .= $line;
                    if ($atStart) {
                        $atStart = false;
                        if (!preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $line, $m)) {
                            $this->errormsg = "Status code line invalid: " . htmlentities($line);
                            fclose($this->sock);
                            return false;
                        }
                        $http_version = $m [1];
                        $this->status = $m [2];
                        $status_string = $m [3];
                        continue;
                    }
                } else {
                    $this->body .= $line;
                }
            }
            fclose($this->sock);

            if ($timeout) {
                $this->errorcode = Nicepay::NICEPAY_READ_TIMEOUT_ERR;
                $this->errormsg = "Socket Timeout(" . $diff . "SEC)";
                return false;
            }
            // return true
            if (!$this->parseResult($this->body)) {
                $this->body = substr($this->body, 4);
                return $this->parseResult($this->body);
            }

            return $this->parseResult($this->body);
        } else {
            return false;
        }
    }

    public function buildQueryString($data)
    {
        $querystring = '';
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $val2) {
                        if ($key != "key")
                            $querystring .= urlencode($key) . '=' . urlencode($val2) . '&';
                    }
                } else {
                    if ($key != "key")
                        $querystring .= urlencode($key) . '=' . urlencode($val) . '&';
                }
            }
            $querystring = substr($querystring, 0, -1);
        } else {
            $querystring = $data;
        }
        return $querystring;
    }

    public function netCancel()
    {
        return true;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getErrorMsg()
    {
        return $this->errormsg;
    }

    public function getErrorCode()
    {
        return $this->errorcode;
    }

    public function parseResult($result)
    {
        return json_decode($result);
    }
}