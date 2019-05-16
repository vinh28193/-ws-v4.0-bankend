<?php


namespace common\payment\providers\wallet;

use common\payment\Payment;
use common\payment\providers\vietnam\NganLuongProvider;
use yii\helpers\Url;

class WalletHideProvider extends NganLuongProvider
{
    public function create(Payment $payment)
    {
        $payment->return_url = Url::to(['payment/payment/return','merchant' => 'nl43']);
        return parent::create($payment);
    }

    public function handle($data)
    {
        parent::handle($data);

    }
}