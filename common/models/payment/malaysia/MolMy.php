<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/11/2018
 * Time: 9:07 AM
 */

namespace common\models\payment\malaysia;


use common\components\UrlComponent;
use common\components\RedisQueue;
use common\models\db\PaymentProvider;
use common\models\model\Order;
use common\models\model\OrderPayment;
use common\models\model\PaymentGatewayRequests;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use Yii;
use common\components\Util;
use yii\helpers\Url;

class MolMy {
    public $payment;
    public $domain;
    public $merchantVerifyId;
    public $secret_key;

    public $env;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        if (\Yii::$app->params['environments_product_molmy'] == false || $this->payment->customer_email == "dev.weshopasia@gmail.com") {
            $this->env = 'dev';
        } else {
            $this->env = 'product';
        }
        $this->domain = Yii::$app->params['molmy'][$this->env]['domain'];
        $this->merchantVerifyId = Yii::$app->params['molmy'][$this->env]['merchantVerifyId'];
        $this->secret_key = Yii::$app->params['molmy'][$this->env]['secret_key'];


    }

    public function createPayment()
    {
        $paymentProvider = PaymentProvider::findOne($this->payment->paymentProvider);

        if ($paymentProvider == null) {
            return json_encode([false, Yii::t('frontend', "Lỗi trong quá trình thanh toán")]);
        }

        $orderid = $this->payment->order_bin;
        if ($this->payment->page == Payment::PAGE_ADDFEE) {
            $orderid = $this->payment->addfee_bin;
        }

        $returnurl = Url::base(true) . '/payment/molmy/return.html';

        $formId = isset($this->payment->formId) ? $this->payment->formId : '';
        $bill_name = isset($this->payment->customer_name) ? $this->payment->customer_name : '';
        $bill_email = isset($this->payment->customer_email) ? $this->payment->customer_email : '';
        $bill_mobile = isset($this->payment->customer_phone) ? $this->payment->customer_phone : '';
        $submitUrl = $this->domain;
        $amount = isset($this->payment->total_amount) ? round($this->payment->total_amount, 2) : 0;
        $vcode = md5($amount . $this->merchantVerifyId . $orderid . $this->secret_key);
        $bill_desc = isset($this->payment->order_note) ? $this->payment->order_note : Yii::t('payment-note-success', "Payment order");
        $data = '<form id="' . $formId . '" style="max-width:700px" action="' . $submitUrl . '" method="post">';
        $data .= '<input type="hidden" name="orderid" value="' . $orderid . '"/>';
        $data .= '<input type="hidden" name="amount" value="' . $amount . '"/>';
        $data .= '<input type="hidden" name="bill_name" value="' . $bill_name . '"/>';
        $data .= '<input type="hidden" name="bill_email" value="' . $bill_email . '"/>';
        $data .= '<input type="hidden" name="bill_mobile" value="' . $bill_mobile . '"/>';
        $data .= '<input type="hidden" name="country" value="id"/>';
        $data .= '<input type="hidden" name="currency" value="MYR">';
        $data .= '<input type="hidden" name="vcode" value="' . $vcode . '">';
        $data .= '<input type="hidden" name="returnurl" value="' . $returnurl . '">';
        $data .= '<input type="hidden" name="bill_desc" value="' . $bill_desc . '">';
        $data .= '</form>';

        return new PaymentResponse(true, $orderid, $data, "Create payment success", "POST", $this->payment);
    }

    public static function checkPayment($request)
    {

        $tranID = isset($request['orderid']) ? $request['tranID'] : '';
        $orderid = isset($request['orderid']) ? $request['orderid'] : '';
        $status = isset($request['orderid']) ? $request['status'] : '';
        $merchant = isset($request['orderid']) ?$request['domain'] : '';
        $amount = isset($request['orderid']) ?$request['amount'] : '';
        $currency = isset($request['orderid']) ?$request['currency'] : '';
        $appcode = isset($request['orderid']) ?$request['appcode'] : '';
        $paydate = isset($request['orderid']) ?$request['paydate'] : '';
        $skey = isset($request['orderid']) ?$request['skey'] : '';

        if (strpos($orderid, 'fee')) {
            $order_fee = OrderPayment::find()->where(['bin_code_addition' => $orderid])->one();
            $order_Rs = $order_fee ? Order::findOne($order_fee->order_id) : "";
        }else{
            $order_Rs = Order::find()->where(['binCode' => $orderid])->one();
        }
        if($order_Rs){
        if (\Yii::$app->params['environments_product_molmy'] == false || $order_Rs->buyerEmail == "dev.weshopasia@gmail.com") {
            $vkey = Yii::$app->params['molmy']['dev']['secret_key'];
        } else {
            $vkey = Yii::$app->params['molmy']['product']['secret_key'];
        }

        $key0 = md5( $tranID.$orderid.$status.$merchant.$amount.$currency );
        $key1 = md5( $paydate.$merchant.$key0.$appcode.$vkey );

        if ($status == "22" || $status == "00") {
            $logCheck = PaymentGatewayRequests::find()->where(['requestId'=>$tranID,'requestType'=>'CALLBACK','responseStatus'=>$status])->asArray()->one();
            // check duplicate call back
            if(!isset($logCheck) && $key1 == $skey){
                if($status == "00"){
                    Order::updatePaymentSuccess($orderid);
                }else{
                    if($order_fee){
                        $order_fee->status = OrderPayment::STATUS_WAITING;
                        $order_fee->save(0);
                    }else{
                        $order_Rs->paymentStatus = OrderPaymentStatus::WAIT_SUCCESS_PAYMENT;
                        $order_Rs->save(0);
                    }
                }
                $log = new PaymentGatewayRequests();
                $log->binCode = $orderid;
                $log->orderId = $order_Rs->id;
                $log->requestType = 'CALLBACK';
                $log->responseStatus = $status;
                $log->requestId = $tranID;
                $log->paymentGateway = 'MOLMY';
                $log->amount = $amount;
                $log->requestUrl = '/payment/molmy/return.html';
                $log->responseContent = json_encode($request);
                $log->responseTime = date('Y-m-d H:i:s');
                $log->storeId = 6; //WSID
                $log->saveRequest();
            } else {
                if (!strpos($orderid, 'fee')) {
                    $binOrder = Order::find()->where(['binCode' => $orderid])->one();
                    $binOrder->sanbox = $_SERVER['HTTP_HOST'] == "weshop.my" ? 0 : 1;
                    $binOrder->save(false);
                    $log = new PaymentGatewayRequests();
                    $log->binCode = $orderid;
                    $log->orderId = $order_Rs->id;
                    $log->requestType = 'CALLBACK_UrlNotify';
                    $log->requestId = $tranID;
                    $log->responseStatus = $status;
                    $log->paymentGateway = 'MOLMY';
                    $log->amount = $amount;
                    $log->requestUrl = $_SERVER['HTTP_HOST'] . '/payment/molmy/return.html';
                    $log->responseContent = json_encode($request);
                    $log->responseTime = date('Y-m-d H:i:s');
                    $log->storeId = 6; //WSID
                    $log->saveRequest();
                }
            }

        } else {
            if($status == 11){
                if($order_fee){
                    $order_fee->status = OrderPayment::STATUS_NEW;
                    $order_fee->save(0);
                }else {
                    $order_Rs->paymentStatus = OrderPaymentStatus::NOT_PAID;
                    $order_Rs->save(0);
                }
            }
            $log = new PaymentGatewayRequests();
            $log->binCode = $orderid;
            $log->orderId = $order_Rs->id;
            $log->requestType = 'CALLBACK_FAIL';
            $log->requestId = $tranID;
            $log->responseStatus = $status;
            $log->paymentGateway = 'MOLMY';
            $log->amount = $amount;
            $log->requestUrl = '/payment/molmy/return.html';
            $log->responseContent = json_encode($request);
            $log->responseTime = date('Y-m-d H:i:s');
            $log->storeId = 6; //WSID
            $log->save();
        }
        }
        return $orderid;
    }

    public function getErrorCode($code)
    {
        // TODO: Implement getErrorCode() method.
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