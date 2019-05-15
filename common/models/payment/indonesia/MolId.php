<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/3/2018
 * Time: 1:18 PM
 */

namespace common\models\payment\indonesia;


use common\components\UrlComponent;
use common\lib\RedisQueue;
use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use Yii;

class MolId
{
    public $payment;
    public $domain;
    public $merchantVerifyId;
    public $secret_key;

    public $env;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        if (\Yii::$app->params['environments_product_molid'] == false || $this->payment->customer_email == "dev.weshopasia@gmail.com") {
            $this->env = 'dev';
            $this->payment->total_amount = 1000;
        } else {
            $this->env = 'product';
        }
        $this->domain = Yii::$app->params['molid'][$this->env]['domain'];
        $this->merchantVerifyId = Yii::$app->params['molid'][$this->env]['merchantVerifyId'];
        $this->secret_key = Yii::$app->params['molid'][$this->env]['secret_key'];


    }

    public function createPayment()
    {
        if ($this->payment->page == Payment::PAGE_ADDFEE) {
            $orderid = $this->payment->addfee_bin;
        } else {
            $orderid = $this->payment->order_bin;
        }
        $returnurl = $this->payment->website->getUrl() . '/payment/molid/return.html';
        $formId = isset($this->payment->formId) ? $this->payment->formId : '';
        $bill_name = isset($this->payment->customer_name) ? $this->payment->customer_name : '';
        $bill_email = isset($this->payment->customer_email) ? $this->payment->customer_email : '';
        $bill_mobile = isset($this->payment->customer_phone) ? $this->payment->customer_phone : '';
//        $submitUrl = $this->payment->config->submitUrl;
        $submitUrl = $this->domain;
        $amount = isset($this->payment->total_amount) ? round($this->payment->total_amount, 0) : 0;
//        $vcode = md5(round($amount, 0) . $this->payment->config->merchainId . $orderid . $this->payment->config->merchainPassword);
        $vcode = md5(round($amount, 0) . $this->merchantVerifyId . $orderid . $this->secret_key);
        $bill_desc = isset($this->payment->order_note) ? $this->payment->order_note : Yii::t('frontend', "Payment order");

        $data = '<form id="' . $formId . '" style="max-width:700px" action="' . $submitUrl . '" method="post">';
        $data .= '<input type="hidden" name="orderid" value="' . $orderid . '"/>';
        $data .= '<input type="hidden" name="amount" value="' . $amount . '"/>';
        $data .= '<input type="hidden" name="bill_name" value="' . $bill_name . '"/>';
        $data .= '<input type="hidden" name="bill_email" value="' . $bill_email . '"/>';
        $data .= '<input type="hidden" name="bill_mobile" value="' . $bill_mobile . '"/>';
        $data .= '<input type="hidden" name="country" value="id"/>';
        $data .= '<input type="hidden" name="currency" value="IDR">';
        $data .= '<input type="hidden" name="vcode" value="' . $vcode . '">';
        $data .= '<input type="hidden" name="returnurl" value="' . $returnurl . '">';
        $data .= '<input type="hidden" name="bill_desc" value="' . $bill_desc . '">';
        $data .= '</form>';

        return new PaymentResponse(true, $orderid, $data, "Create payment success", "POST", $this->payment);
    }

    public static function checkPayment($request)
    {
        if(isset($request['statusCheckSuccess']) && $request['statusCheckSuccess'] == 00){
            Order::updatePaymentSuccess($request['orderid']);
            return $request['orderid'];
        }
        $order = isset($request['orderid']) ? $request['orderid'] : "";
        $tranID = isset($request['tranID']) ? $request['tranID'] : '';
        $status = isset($request['status']) ? $request['status'] : '';
        $amount = isset($request['amount']) ? $request['amount'] : 0;
        if ($status == "22" || $status == "00") {
            $logCheck = PaymentGatewayRequests::find()->where(['requestId'=>$tranID])->asArray()->one();
            // check duplicate call back
            $order_Rs = Order::find()->where(['binCode'=>$order])->asArray()->one();
            if(!isset($logCheck)) {
                $envs = 'dev';
                if (\Yii::$app->params['environments_product_molid'] == false || $order_Rs['buyerEmail'] == "dev.weshopasia@gmail.com") {
                    $url = 'https://sandbox.molpay.com/MOLPay/query/q_by_oid.php';
                    $envs = 'dev';
                } else {
                    $url = 'https://secure.molpay.co.id/MOLPay/query/q_by_oid.php';
                    $envs = 'product';
                }
                $veryKey = Yii::$app->params['molid'][$envs]['verify_key'];
                $skey = md5($order . $request['domain'] . $veryKey . $amount);

                $queue = new RedisQueue(\Yii::$app->params['TRANSACTION_QUEUE_MOL']);
                $data_push = [
                    'oID' => $order,
                    'domain' => $request['domain'],
                    'skey' => $skey,
                    'amount' => $amount,
                    'url' => $url,
                    'veryKey' => $veryKey,
                    'timePushRedis' => date('Y-m-d H:i:s')
                ];
                $queue->push($data_push);
                //Order::updatePaymentSuccess($order);
                $log = new PaymentGatewayRequests();
                $log->binCode = $order;
                $log->requestType = 'CALLBACK';
                $log->requestId = $tranID;
                $log->paymentGateway = 'MOLID';
                $log->amount = $amount;
                $log->requestUrl = '/payment/molid/return.html';
                $log->responseContent = json_encode($request).$_SERVER['HTTP_HOST'];
                $log->responseTime = date('Y-m-d H:i:s');
                $log->storeId = 6; //WSID
                $log->saveRequest();
            }else{
                $binOrder = Order::find()->where(['binCode'=>$order])->one();
                $binOrder->sanbox = $_SERVER['HTTP_HOST'] == "weshop.co.id" ? 0 : 1;
                $binOrder->save(false);
                $log = new PaymentGatewayRequests();
                $log->binCode = $order;
                $log->requestType = 'CALLBACK';
                $log->requestId = $tranID;
                $log->paymentGateway = 'MOLID';
                $log->amount = $amount;
                $log->requestUrl = $_SERVER['HTTP_HOST']. '/payment/molid/return.html';
                $log->responseContent = json_encode($request);
                $log->responseTime = date('Y-m-d H:i:s');
                $log->storeId = 6; //WSID
                $log->saveRequest();
            }

        } else {
            $log = new PaymentGatewayRequests();
            $log->binCode = $order;
            $log->requestType = 'CALLBACK_FAIL';
            $log->requestId = $tranID;
            $log->paymentGateway = 'MOLID';
            $log->amount = $amount;
            $log->requestUrl = '/payment/molid/return.html';
            $log->responseContent = json_encode($request);
            $log->responseTime = date('Y-m-d H:i:s');
            $log->storeId = 7; //WSID
            $log->save();
        }

        return $order;
    }
}