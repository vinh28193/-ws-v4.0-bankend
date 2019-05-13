<?php


namespace frontend\modules\checkout;

use yii\base\BaseObject;

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
    public static function handle($data);
}