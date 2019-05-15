<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 12/28/2017
 * Time: 2:50 PM
 */

namespace common\models\payment\philippines;


use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\payment\interfaces\Merchant;
use common\models\payment\PaymentResponse;
use common\models\payment\Payment;

class DragonPay implements Merchant
{
    protected $merchant_id;
    protected $secret_key;
    protected $payment_url;
    public $txnid;
    public $amount;
    public $ccy = "PHP";
    public $email;
    public $desc = "Payment order";
    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->txnid = $payment->order_bin;
        if ($payment->page == Payment::PAGE_ADDFEE) {
            $this->txnid = $request->addfee_bin;
        }
        $this->email = isset($payment->customer_email) ? $payment->customer_email : '';
        $this->amount = number_format(isset($payment->total_amount) ? $payment->total_amount : 0, 2, '.', '');
        $this->desc = "Payment order";

//        if (!YII_DEBUG) {
//            $this->payment_url = "";
//            $this->secret_key = "";
//            $this->merchant_id = "";
//        } else {
        $this->payment_url = $payment->config->submitUrl;
        $this->merchant_id = $payment->config->merchainId;
        $this->secret_key = $payment->config->merchainPassword;
//        }
    }

    public function buildParam()
    {
        return $this->payment_url . "?merchantid=" . urlencode($this->merchant_id) .
            "&txnid=" . urlencode($this->txnid) .
            "&amount=" . urlencode($this->amount) .
            "&ccy=" . urlencode($this->ccy) .
            "&description=" . urlencode($this->desc) .
            "&email=" . urlencode($this->email) .
            "&param1=dragonpay" .
            "&digest=" . urlencode($this->getSign());
    }


    public function getSign()
    {
        $digest_str = "$this->merchant_id:$this->txnid:$this->amount:$this->ccy:$this->desc:$this->email:$this->secret_key";
        return sha1($digest_str);
    }

    public function createPayment()
    {
        return new PaymentResponse(true, $this->txnid, $this->buildParam(), "create request success", "GET", $this->payment);
    }

    public static function checkPayment($request = null)
    {
        if (!isset($request['txnid']) || !isset($request['refno']) || !isset($request['status']) || !isset($request['message']) || !isset($request['digest']) || !isset($request['param1'])) {
            return false;
        }
//        $passwd='mtwXKdWIsKDRZu';
//        $digest_str = "{$request['txnid']}:{$request['refno']}:{$request['status']}:{$request['message']}:{$passwd}";
//        $digest = sha1($digest_str);
//        // So sanh dieu kien de thoa man thanh toan thanh cong
//        if (!empty($digest) && $request['digest'] != $digest) {
//            return false;
//        }
        $log = PaymentGatewayRequests::getRequest($request['txnid']);
        if (empty($log)) {
            $log = new PaymentGatewayRequests();
            $log->storeId = 9;
            $log->responseContent = json_encode($request);
            $log->requestId = $request['txnid'];
            $log->requestType = 'CALLBACK';
            $log->paymentGateway = 'DRAGONPAY';
            $log->requestUrl = 'callback/dragonpay/notify.html';
            $log->responseStatus = $request['status'];
            $log->createTime = date('Y-m-d H:i:s');
            $log->responseTime = date('Y-m-d H:i:s');
            $log->saveRequest();
        } else {
            $log->responseContent = json_encode($request);
            $log->requestType = 'CALLBACK';
            $log->requestUrl = 'callback/dragonpay/notify.html';
            $log->responseStatus = $request['status'];
            $log->responseTime = date('Y-m-d H:i:s');
            $log->saveRequest();
        }

        //  if ($request['digest'] != $this->getSign()) return false;
        switch ($request['status']) {
            case self::STATUS_SUCCESS:
                Order::updatePaymentSuccess($request['txnid']);
                break;
        }
    }

    public function getErrorCode($code)
    {
        $data = [
            '000' => 'Success',
            '101' => 'Invalid payment gateway id',
            '102' => 'Incorrect secret key',
            '103' => 'Invalid reference number',
            '104' => 'Unauthorized access',
            '105' => 'Invalid token',
            '106' => 'Currency not supported',
            '107' => 'Transaction cancelled',
            '108' => 'Insufficient funds',
            '109' => 'Transaction limit exceeded',
            '110' => 'Error in operation',
            '111' => 'Security Error',
            '112' => 'Invalid parameters',
            '201' => 'Invalid Merchant Id',
            '202' => 'Invalid Merchant Password'
        ];
        return $data[$code . ''];
    }

    public function getStatusCode($code)
    {
        $data = [
            'S' => 'Success',
            'F' => 'Failure',
            'P' => 'Pending',
            'U' => 'Unknown',
            'R' => 'Refund',
            'K' => 'Chargeback',
            'V' => 'Void',
            'A' => 'Authorized'
        ];
        return $data[$code];
    }

    const STATUS_SUCCESS = "S";
    const STATUS_FAILURE = "F";
    const STATUS_PENDING = "P";
    const STATUS_REFUND = "R";
}