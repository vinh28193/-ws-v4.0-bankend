<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 12/28/2017
 * Time: 3:21 PM
 */

namespace common\models\payment;


use common\components\UrlComponent;
use common\models\model\PaymentGatewayRequests;

class PaymentResponse
{

    public $success;
    public $token;
    public $checkoutUrl;
    public $message;
    public $method;

    public $gaData;
    public $binCode;
    public $urlStep3;
    public $storeId,$storeCode;

    function __construct($success = false, $token = null, $checkoutUrl = null, $message = null, $method = "GET", Payment $payment = null)
    {
        $this->success = $success;
        $this->token = $token;
        $this->checkoutUrl = $checkoutUrl;
        $this->message = $message;
        $this->method = $method;

        if ($payment != null) {
            $this->storeId = \Yii::$app->store->getStoreId();
            $this->storeCode = \Yii::$app->store->mameSymbol();
            $this->binCode = $payment->order_bin;
            $this->urlStep3 = \Yii::$app->store->getUrl().UrlComponent::step3_bill($payment->order_bin);
            $log = new PaymentGatewayRequests();
            $log->orderId = $payment->order_id;
            $log->binCode = $payment->page == Payment::PAGE_ADDFEE ? $payment->addfee_bin : $payment->order_bin;
            $log->requestId = $payment->order_bin;
            $log->requestContent = $checkoutUrl;
            $log->requestType = $payment->page == Payment::PAGE_ADDFEE ? "ADDFEE_TO_GW" : PaymentGatewayRequests::TYPE_SEND_REQUEST;
            $log->storeId = \Yii::$app->store->getStoreId();
            $log->paymentGateway = $payment->paymentProvider;
            $log->paymentMethod = $payment->paymentMethod;
            $log->paymentBank = $payment->bankCode;
            $log->createTime=date('Y-m-d H:i:s');
            $log->amount = $payment->total_amount;
            $log->saveRequest();
        }

    }
}
