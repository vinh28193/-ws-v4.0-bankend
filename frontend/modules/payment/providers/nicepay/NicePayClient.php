<?php


namespace frontend\modules\payment\providers\nicepay;

use yii\base\BaseObject;
use yii\helpers\Json;
use Exception;

class NicePayClient extends BaseObject
{
    const TIMEOUT_CONNECT = 15;
    const TIMEOUT_READ = 25;
    const READ_TIMEOUT_ERR = 10200;

    public $apiUrl = 'https://www.nicepay.co.id/nicepay/api';

    public $status;
    public $headers = "";
    public $body = "";


    public $sock = 0;
    public $port = 443;

    public $errors;

    public function createUrl($url)
    {
        return $this->apiUrl . '/' . $url;
    }

    private $_data;

    public function getData()
    {
        if ($this->_data === null) {
            $this->_data = new ParamCollection();
        }
        return $this->_data;
    }

    public function apiRequest($url)
    {
        if (!empty($this->errors)) {
            return false;
        }
        $url = $this->createUrl($url);
        if ($this->openSocket($url) !== true) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST); // www.nicepay.co.id
        $uri = parse_url($url, PHP_URL_PATH); // /nicepay/api/onePass.do
        $this->headers = "";
        $this->body = "";
        $postData = $this->buildQueryString();

        /* Write */
        $request = "POST " . $uri . " HTTP/1.0\r\n";
        $request .= "Connection: close\r\n";
        $request .= "Host: " . $host . "\r\n";
        $request .= "Content-type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-length: " . strlen($postData) . "\r\n";
        $request .= "Accept: */*\r\n";
        $request .= "\r\n";
        $request .= $postData . "\r\n";
        $request .= "\r\n";
        if ($this->sock) {

            fwrite($this->sock, $request);

            /* Read */
            stream_set_blocking($this->sock, false);

            $atStart = true;
            $IsHeader = true;
            $timeout = false;
            $start_time = time();
            while (!feof($this->sock) && !$timeout) {
                $line = fgets($this->sock, 4096);
                $diff = time() - $start_time;
                if ($diff >= self::TIMEOUT_READ) {
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
                            $this->errors[] = "Status code line invalid: " . htmlentities($line);
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
                $this->errors[] = "Socket Timeout(" . $diff . "SEC)";
                return false;
            }
            // return true
            try {
                return $this->parseResult($this->body);
            } catch (Exception $exception) {
                $this->body = substr($this->body, 4);
                return $this->parseResult($this->body);
            }
        } else {
            return false;
        }
    }

    public function openSocket($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $tryCount = 0;
        if (!$this->sock = @fsockopen("ssl://" . $host, $this->port, $errno, $errstr, self::TIMEOUT_CONNECT)) {
            while ($tryCount < 5) {
                if ($this->sock = @fsockopen("ssl://" . $host, $this->port, $errno, $errstr, self::TIMEOUT_CONNECT)) {
                    return true;
                }
                sleep(2);
                $tryCount++;
            }
            switch ($errno) {
                case -3 :
                    $this->errors[] = 'Socket creation failed (-3)';
                case -4 :
                    $this->errors[] = 'DNS lookup failure (-4)';
                case -5 :
                    $this->errors[] = 'Connection refused or timed out (-5)';
                default :
                    $this->errors[] = ('Connection failed (' . $errno . ') ' . $errstr);
            }
            return false;
        }
        return true;
    }

    public function chargeCard()
    {
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
        return $this->apiRequest('onePass.do');
    }

    public function requestVa()
    {
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
        return $this->apiRequest('orderRegist.do');
    }

    public function cancelVA()
    {
        $this->checkParam('iMid', '01');
        $this->checkParam('amt', '04');
        $this->checkParam('merchantToken', '28');
        $this->checkParam('tXid', '36');
        return $this->apiRequest('onePassAllCancel.do');
    }

    public function checkPaymentStatus()
    {
        $this->checkParam('iMid', '01');
        $this->checkParam('amt', '04');
        $this->checkParam('referenceNo', '06');
        $this->checkParam('merchantToken', '28');
        $this->checkParam('tXid', '36');
        return $this->apiRequest('onePassStatus.do');
    }

    public function buildQueryString()
    {
        $querystring = '';
        $data = $this->getData()->toArray();
        if (!empty($data)) {
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
        if (!empty($this->errors)) {
            return NicePayUtils::oneLine($this->errors);
        }
        return null;
    }

    public function parseResult($result)
    {
        return Json::decode($result, true);
    }

    public function checkParam($requestData, $errorNo)
    {
        if (null === $this->getData()->get($requestData, null)) {
            $this->getErrorCodes($errorNo);
        }
    }

    public function getErrorCodes($code)
    {
        $errors = array(
            // That always Unknown Error :)
            '00' => 'Unknown error. Contact it.support@ionpay.net.',
            // General Mandatory parameters
            '01' => '(iMid) is not set. Please set (iMid).',
            '02' => '(payMethod) is not set. Please set (payMethod).',
            '03' => '(currency) is not set. Please set (currency).',
            '04' => '(amt) is not set. Please set (amt).',
            '05' => '(instmntMon) is not set. Please set (instmntMon).',
            '06' => '(referenceNo) is not set. Please set (referenceNo).',
            '07' => '(goodsNm) is not set. Please set (goodsNm).',
            '08' => '(billingNm) is not set. Please set (billingNm).',
            '09' => '(billingPhone) is not set. Please set (billingPhone).',
            '10' => '(billingEmail) is not set. Please set (billingEmail).',
            '11' => '(billingAddr) is not set. Please set (billingAddr).',
            '12' => '(billingCity) is not set. Please set (billingCity).',
            '13' => '(billingState) is not set. Please set (billingState).',
            '14' => '(billingCountry) is not set. Please set (billingCountry).',
            '15' => '(deliveryNm) is not set. Please set (deliveryNm).',
            '16' => '(deliveryPhone) is not set. Please set (deliveryPhone).',
            '17' => '(deliveryAddr) is not set. Please set (deliveryAddr).',
            '18' => '(deliveryCity) is not set. Please set (deliveryCity).',
            '19' => '(deliveryState) is not set. Please set (deliveryState).',
            '21' => '(deliveryPostCd) is not set. Please set (deliveryPostCd).',
            '22' => '(deliveryCountry) is not set. Please set (deliveryCountry).',
            '23' => '(callBackUrl) is not set. Please set (callBackUrl).',
            '8' => '(dbProcessUrl) is not set. Please set (dbProcessUrl).',
            '24' => '(vat) is not set. Please set (vat).',
            '25' => '(fee) is not set. Please set (fee).',
            '26' => '(notaxAmt) is not set. Please set (notaxAmt).',
            '27' => '(description) is not set. Please set (description).',
            '28' => '(merchantToken) is not set. Please set (merchantToken).',
            '29' => '(bankCd) is not set. Please set (bankCd).',
            // Mandatory parameters to Check Order Status
            '30' => '(tXid) is not set. Please set (tXid).',
        );
        if (isset($errors[$code]) && ($error = $errors[$code]) !== null) {
            $this->errors[] = $error;
        }
    }
}