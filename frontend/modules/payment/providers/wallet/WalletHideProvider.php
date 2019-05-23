<?php


namespace frontend\modules\payment\providers\wallet;

use common\models\PaymentTransaction;
use common\models\WalletTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\vietnam\NganLuongProvider;
use yii\helpers\Url;

class WalletHideProvider extends NganLuongProvider
{
    public function create(Payment $payment)
    {
        $res =  parent::create($payment);
        if($res['success'] === true && isset($res['data']['token'])){
            WalletTransaction::updateAll(['payment_transaction' => $res['data']['token']],['wallet_transaction_code' => $payment->transaction_code]);
        }
        return $res;
    }

    public function handle($data)
    {
        $res = parent::handle($data);
        if ($res['success'] === true && ($paymentTransaction = $res['data']['transaction']) instanceof PaymentTransaction) {
            /** @var  $paymentTransaction PaymentTransaction */
            $wallet = new WalletService([
                'transaction_code' => $paymentTransaction->topup_transaction_code,
                'payment_transaction' => $paymentTransaction->third_party_transaction_code,
                'total_amount' => round((int)$paymentTransaction->transaction_amount_local),
                'payment_method' => $paymentTransaction->payment_method,
                'payment_provider' => $paymentTransaction->payment_provider,
                'bank_code' => $paymentTransaction->payment_bank_code,
            ]);
            if (($rs = $wallet->pushToTopUpNoAuth())['success'] === true) {
                $wallet->payment_transaction = $paymentTransaction->transaction_code;
                $wallet->createSafePaymentTransaction();
            }
        }
        return $res;
    }
}