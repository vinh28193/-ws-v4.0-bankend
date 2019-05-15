<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/3/2018
 * Time: 12:02 PM
 */

namespace common\models\payment\indonesia;


use common\components\RedisQueue;
use common\components\UrlComponent;
use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\model_logs\PaymentLog;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use yii\helpers\Url;

class Mcpay
{


    public $url = 'https://mcbill.sandbox.id.mcpayment.net/pay/weshopdev?';

    public $vcode = '';
    public $amount = '3000';
    public $merchantID = 'weshopdev';
    public $order_number;
    public $verify_key = '92ccabfc283a8d6b8a5c0d3b656ad05e';


    public $sKey; //khoa bao mat tranh giao dịch gia m

    public $key0;
    public $tranID;
    public $ordered;
    public $status;
    public $currency;


    public $paydate;
    public $domain;
    //public $key0;
    public $appcode;
    public $vKey;
    public $orderid = 'wstest001';
    public $bill_name = 'bui quang quyet';
    public $bill_mobile = '0964277193';
    public $bill_desc = 'ws';
    public $country = "ID";
    public $cur = "IDR";
    public $bill_email = "buiquangquyet@gmail.com";
    public $returnurl = 'http://weshop.beta.id';
    public $langcode = "en";
    public $payment;


    protected  $log;

    public function __construct(Payment $payment = null)
    {
        $this->payment = $payment ? $payment : $this->payment;
        $this->vcode = md5($this->amount & $this->merchantID & $this->orderid & $this->verify_key);
    }

    public function renderVcode()
    {
        $this->__construct();
    }

    public function GetUrl()
    {
        $rs = $this->url . "amount=" . $this->amount . "&" .
            "orderid=" . urlencode($this->orderid) . "&" .
            "bill_name=" . urlencode($this->bill_name) . "&" .
            "bill_email=" . urlencode($this->bill_email) . "&" .
            "bill_mobile=" . urlencode($this->bill_mobile) . "&" .
            "bill_desc=" . urlencode($this->bill_desc) . "&" .
            "country=" . $this->country . "&" .
            "returnurl=" . urlencode($this->returnurl) . "&" .
            "vcode=" . $this->vcode . "&" .
            "cur=" . $this->cur . "&" .
            "langcode=" . $this->langcode;

        return $rs;
    }

    public function createPayment()
    {
        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $email_test = \Yii::$app->params['EmailTest'];
        if (\Yii::$app->params['environments_product_mcpay']==false || (in_array($this->payment->customer_email,$email_test)) ) {
            $environments = 'dev';
        }else{
            $environments = 'product';
        }
        $this->url = \Yii::$app->params['mcpay'][$environments]['url'];
        if ($this->payment->page == Payment::PAGE_ADDFEE) {
            $this->orderid = $this->payment->addfee_bin;
        } else {
            $this->orderid = $this->payment->order_bin;
        }
        $this->returnUrl = Url::base(true) . '/api/checkout/mcpay/callbackpayment.html';
        $this->verify_key = \Yii::$app->params['mcpay'][$environments]['verify_key'];
        $this->amount = $this->payment->total_amount;
        $this->bill_name = $this->payment->customer_name;
        $this->bill_email = $this->payment->customer_email;
        $this->bill_mobile = $this->payment->customer_phone;
        $this->bill_desc = $this->payment->order_note;
        $this->renderVcode();
        $rs = new \stdClass();
        $rs->success = 1;
        $rs->message = 'Payment order';
        $rs->data = new \stdClass();
        $rs->data->data->requestURL = $this->GetUrl();
        $rs->checkoutUrl = $this->GetUrl();

        //todo log McPay
        $this->log = New PaymentLog();
        $this->log->status = 'success';
        $log = PaymentLog::find()->where(['provider'=>'MCPAY'])->andWhere(['bincode'=>$this->orderid])->andWhere(['action'=>$this->url])->andWhere(['status'=>$this->log->status])->one();
        $this->log->provider = 'MCPAY';
        $this->log->bincode = $this->orderid;
        $this->log->action = $this->url;
        $this->log->request = $this;
        $this->log->request_encode = $this;
        $this->log->respone = $rs->data->data->requestURL;
        $this->log->respone_encode = $rs->data->data->requestURL;
        $this->log->time = date('Y-m-d H:i:s');
        if(!$log){
            $this->log->save(false);
        }

        //Log4P::pushLogs($rs, 'PAYMENT-MAKE/Mcpay/' . date('Y-m-d') . '/rs');

        // cho data vao queue
        $data_push['type'] = 'trunggianweshop';
        $data_push['token'] = '';
        $queue = new RedisQueue(\Yii::$app->params['QUEUE_TRANSACTION_MCPAY']);
        $queue->push($data_push);
        return new PaymentResponse(true, $this->orderid, $rs->checkoutUrl, "Call mcpay success", "GET", $this->payment);
    }

    public static function checkPayment($post)
    {
        //Log4P::pushLogs($post, 'PAYMENT-CALLBACK/Mcpay/' . date('Y-m-d') . $_POST['orderid']);
        if (\Yii::$app->params['environments_product_mcpay'] != true) {
            $environments = 'dev';
        } else {
            $environments = 'product';
        }
        $vkey = \Yii::$app->params['mcpay'][$environments]['verify_key'];
        $tranID = $_POST['tranID'];
        $orderid = $_POST['orderid'];
        $status = $_POST['status'];
        $domain = $_POST['domain'];
        $amount = $_POST['amount'];
        $currency = $_POST['currency'];
        $appcode = $_POST['appcode'];
        $paydate = $_POST['paydate'];
        $skey = $_POST['skey'];
        /***********************************************************
         * To verify the data integrity sending by MCBILL
         ************************************************************/
        $key0 = md5( $tranID.$orderid.$status.$domain.$amount.$currency );
        $key1 = md5( $paydate.$domain.$key0.$appcode.$vkey );
        if( $skey != $key1 )
            $status= -1;
        if ( $status == "00" ) {
            Order::updatePaymentSuccess($orderid);
        }
        $logs = new PaymentGatewayRequests();
        $logs->binCode =$orderid;
        $logs->requestId=$tranID;
        $logs->responseContent = json_encode($post);
        $logs->createTime=date('Y-m-d H:i:s');
        $logs->storeId=7;
        $logs->responseContent = json_encode($_POST);
        $logs->paymentGateway="MCPAY";
        $logs->paymentMethod="MCPAY";
        $logs->requestType="CALLBACK";
        $logs->saveRequest();
        return $orderid;
    }

}