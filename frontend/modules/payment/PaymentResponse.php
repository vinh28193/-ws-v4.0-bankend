<?php


namespace frontend\modules\payment;
;


class PaymentResponse
{

    const TYPE_NORMAL = 'normal';
    const TYPE_SUBMIT = 'submit';
    const TYPE_ENDPOINT = 'endpoint';
    const TYPE_REDIRECT = 'redirect';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_POPUP = 'POPUP';

    public $success;
    public $message;
    public $paymentTransaction;
    public $redirectType;
    public $token;
    public $redirectMethod;
    public $checkoutUrl;
    public $returnUrl;
    public $cancelUrl;


    function __construct($success, $message, $paymentTransaction = null, $redirectType = self::TYPE_NORMAL, $redirectMethod = self::METHOD_GET, $token = null, $checkoutUrl = null, $returnUrl = null, $cancelUrl = null, Payment $payment = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->paymentTransaction = $paymentTransaction;
        $this->redirectMethod = $redirectMethod;
        $this->redirectType = $redirectType;
        $this->token = $token;
        $this->checkoutUrl = $checkoutUrl;
        $this->returnUrl = $returnUrl;
        $this->cancelUrl = $cancelUrl;
    }

}