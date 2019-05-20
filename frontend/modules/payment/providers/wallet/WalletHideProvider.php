<?php


namespace frontend\modules\payment\providers\wallet;

use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\vietnam\NganLuongProvider;
use yii\helpers\Url;

class WalletHideProvider extends NganLuongProvider
{
    public function create(Payment $payment)
    {
        return parent::create($payment);
    }

    public function handle($data)
    {
        $res = parent::handle($data);
        if ($res['success'] === true && ($paymentTransaction = $res['data']['transaction']) instanceof PaymentTransaction) {
            /** @var  $paymentTransaction PaymentTransaction */
            $wallet = new WalletService([
                'merchant_id' => WalletService::MERCHANT_IP_DEV,
                'transaction_code' => $paymentTransaction->transaction_code,
                'total_amount' => $paymentTransaction->transaction_amount_local,
                'payment_method' => $paymentTransaction->payment_method,
                'payment_provider' => $paymentTransaction->payment_provider,
                'bank_code' => $paymentTransaction->payment_bank_code,
                'otp_type' => 3,
            ]);
            $wallet->createPaymentTransaction();
        }
        return $res;
    }
}