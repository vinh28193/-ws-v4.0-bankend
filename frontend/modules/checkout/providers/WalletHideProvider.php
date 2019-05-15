<?php


namespace frontend\models\checkout\providers;


use frontend\modules\checkout\Payment;
use yii\base\BaseObject;
use frontend\modules\checkout\PaymentProviderInterface;

class WalletHideProvider extends BaseObject implements  PaymentProviderInterface
{
    public function create(Payment $payment)
    {
        // TODO: Implement create() method.
    }

    public static function handle($data)
    {
        // TODO: Implement handle() method.
    }

}