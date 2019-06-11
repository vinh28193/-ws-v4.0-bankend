<?php


namespace frontend\modules\payment\controllers;


use frontend\modules\payment\Payment;

class ValidateController extends BasePaymentController
{

    public function actionCheckField($provider)
    {
        $provider = (int)$provider;
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);
    }

    public function nlGetRequestField($payment)
    {

    }
}