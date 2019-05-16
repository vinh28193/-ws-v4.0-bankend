<?php


namespace common\payment\providers;


use frontend\modules\checkout\PaymentProviderInterface;
use frontend\modules\checkout\Payment;

class Wallet extends \yii\base\BaseObject implements PaymentProviderInterface
{

    public function create(Payment $payment)
    {
        // TODO: Implement create() method.
    }

    public  function handle($data)
    {
        // TODO: Implement verify() method.
    }
}