<?php


namespace common\payment\providers\wallet;

use yii\base\BaseObject;
use common\payment\Payment;
use common\payment\PaymentProviderInterface;

class WalletProvider extends BaseObject implements PaymentProviderInterface
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