<?php


namespace frontend\modules\checkout;


class PaymentResponse
{

    public $success;
    public $token;
    public $checkoutUrl;
    public $message;
    public $method;

    public $gaData;
    public $transactionCode;
    public $returnUrl;

    function __construct($success = false, $token = null, $checkoutUrl = null, $message = null, $method = "GET", Payment $payment = null)
    {
        $this->success = $success;
        $this->token = $token;
        $this->checkoutUrl = $checkoutUrl;
        $this->message = $message;
        $this->method = $method;
    }

}