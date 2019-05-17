<?php


namespace common\payment\providers\wallet;

use common\payment\Payment;
use common\payment\providers\vietnam\NganLuongProvider;
use yii\helpers\Url;

class WalletHideProvider extends NganLuongProvider
{
    public function create(Payment $payment)
    {
        return parent::create($payment);
    }

    public function handle($data)
    {
        return parent::handle($data);

    }
}