<?php


namespace frontend\modules\payment;


interface PaymentProviderInterface
{


    /**
     * @param Payment $payment
     * @return mixed
     */
    public function create(Payment $payment);

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data);
}