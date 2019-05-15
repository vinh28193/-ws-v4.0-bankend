<?php


namespace frontend\modules\checkout;


interface PaymentProviderInterface
{


    /**
     * @param Payment $transaction
     * @return mixed
     */
    public function create(Payment $transaction);

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data);
}