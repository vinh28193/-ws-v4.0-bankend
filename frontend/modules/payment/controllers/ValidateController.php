<?php


namespace frontend\modules\payment\controllers;


use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\nganluong\ver3_2\NganLuongClient;

class ValidateController extends BasePaymentController
{

    public function actionCheckField($provider)
    {
        $provider = (int)$provider;
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);
        if ($provider === 46) {
            return $this->nlGetRequestField($payment);
        }
        return false;
    }

    /**
     * @param $payment Payment
     * @return array|string
     */
    public function nlGetRequestField($payment)
    {
        $client = new NganLuongClient();
        return $client->GetRequestField($payment->payment_bank_code);
    }
}