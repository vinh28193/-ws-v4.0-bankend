<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 13/08/2018
 * Time: 08:46
 */

namespace common\models\payment\vietnam;


use common\components\Alepay\Crypt\Crypt_RSA;
use common\components\Cache;
use common\components\RedisQueue;
use common\components\ReponseData;
use common\components\UrlComponent;
use common\models\model\Order;
use common\models\model\PaymentProvider;
use common\models\payment\PaymentResponse;
use common\models\payment\vietnam\alepaylib\AlepayCheckoutForm;
use common\models\payment\vietnam\alepaylib\AlepayCheckPaymentForm;
use yii\helpers\Json;
use yii\helpers\Url;
use common\models\payment\vietnam\alepaylib\AlepayGate;

class Alepay
{
    public $buyerEmail;

    private static $instant = null;

    public function CreatePayment($payment)
    {
        //todo parse payment->alepay
        //$makePayment = AlepayClient::makePayment($this);
        $this->buyerEmail = $payment->customer_email;
        $obj = $this->getInstant();
        $ObjectCheckout = $this->ParseAlepaytoCheckoutForm($payment);


        $resp = $obj->CheckOut($ObjectCheckout);

        if (empty($resp)) {
            return new PaymentResponse(false, null, null, "Lỗi thanh toán");
        }
        if (!empty($resp->token)) {
            try {
                $queue = new RedisQueue(\Yii::$app->params['TRANSACTION_QUEUE_ALEPAY']);
                $dataPush['date'] = date('Y-m-d H:i:s');
                $dataPush['data'] = $resp->token;
                $dataPush['checkoutUrl'] = $resp->checkoutUrl;
                $dataPush['order_bin'] = $payment->order_bin;
                $queue->push($dataPush);
            } catch (\Exception $e) {
                \Yii::debug($e->getTraceAsString());
            }
        }
        $makePayment = $resp->checkoutUrl;

        return new PaymentResponse(true, 'Success', $makePayment, "", "GET");
    }

    public function getInstant()
    {
        if (static::$instant == null) {
            $email_test = \Yii::$app->params['EmailTest'];
            if (\Yii::$app->params['environments_product_alepay']==false || (in_array($this->buyerEmail,$email_test)) ) {
                static::$instant = new AlepayGate(false);
            } else {
                static::$instant = new AlepayGate(true);
            }
        }
        return static::$instant;
    }

    public function ParseAlepaytoCheckoutForm($payment){
        $alepayCheckoutObject = new AlepayCheckoutForm();
        $alepayCheckoutObject->orderCode = $payment->order_bin;
        $alepayCheckoutObject->checkoutType = 0;
        $alepayCheckoutObject->amount =$payment->total_amount;
        $alepayCheckoutObject->orderDescription = "Giao dịch cho mã " . $payment->order_bin . " trên hệ thống";
        $alepayCheckoutObject->buyerCity = $payment->customer_city;
        $alepayCheckoutObject->buyerName = trim($payment->customer_name);
        $alepayCheckoutObject->buyerEmail = $payment->customer_email;
        $alepayCheckoutObject->buyerPhone = $payment->customer_phone;
        $alepayCheckoutObject->buyerAddress = $payment->customer_address;
        $alepayCheckoutObject->totalItem = !empty($payment->total_quantity)?$payment->total_quantity:1;
        if (!empty($payment->installment_bank) && !empty($payment->installment_method) && !empty($payment->installment_month)) {
            //$request->installment = $infoPayment->installment;
            $alepayCheckoutObject->bankCode = $payment->installment_bank;
            $alepayCheckoutObject->paymentMethod = $payment->installment_method;
            $alepayCheckoutObject->month = $payment->installment_month;
            $alepayCheckoutObject->installment = true;
            $alepayCheckoutObject->paymentHours = 6;
        } else {
            $alepayCheckoutObject->checkoutType = 1;
            $alepayCheckoutObject->installment = false;
            //OrderAlepayAddfee::updateByOrder($this->orderCode, false, $this->amount, 0);
        }
        $alepayCheckoutObject->returnUrl = Url::base(true) . '/payment/alepay/return.html';
        $alepayCheckoutObject->cancelUrl = Url::base(true) . UrlComponent::step3_bill($payment->order_bin);
        return $alepayCheckoutObject;
    }

    public function CheckPayment($token,$binCode = null){
        //todo getOrder
        if(!empty($binCode)){
            $order = Order::GetbyBinCode($binCode);
            $this->buyerEmail = $order->buyerEmail;
        }


        //todo parse
        $alepayForm = new AlepayCheckPaymentForm();
        $alepayForm->transactionCode = $token;
        $obj = $this->getInstant();
        $obj->binCode = $binCode;
        $datars = $obj->CheckInfoPayment($alepayForm);
        if(isset($datars->status) && $datars->status == '000'){
            return ReponseData::reponseMess(true,'Check payment thành công',$datars) ;
        }else{
            return ReponseData::reponseMess(false,'Check payment lỗi',$datars) ;
        }

    }

}