<?php


namespace frontend\modules\payment\providers\vietnam;


use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\wallet\WalletService;
use yii\helpers\ArrayHelper;

class WalletClientProvider extends NganLuongProvider
{

    /**
     * @param Payment $payment
     * @return mixed
     */
    public function create(Payment $payment)
    {
        return parent::create($payment);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        $walletS = new WalletService();
        $walletS->payment_transaction = ArrayHelper::getValue($data,'token');
        $walletS->transaction_code = ArrayHelper::getValue($data,'order_code');
        $rs = $walletS->pushToTopUpNoAuth();
        return $rs;
    }
}