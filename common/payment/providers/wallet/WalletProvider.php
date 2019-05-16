<?php


namespace common\payment\providers\wallet;

use yii\base\BaseObject;
use common\payment\Payment;
use common\payment\PaymentProviderInterface;

class WalletProvider extends BaseObject implements PaymentProviderInterface
{
    const WALLET_CHECKOUT_OTP = '/account/wallet/otp-verify';

    public function create(Payment $payment)
    {
        $walletService = new WalletService([
            'merchant_id' => WalletService::MERCHANT_IP_PRO,
            'transaction_code' => $payment->transaction_code,
            'total_amount' => $payment->total_amount - $payment->total_discount_amount,
            'payment_method' => $payment->payment_method,
            'payment_provider' => $payment->payment_provider,
            'bank_code' => $payment->payment_bank_code,
            'otp_type' => $payment->otp_verify_method
        ]);
        $response = $walletService->createPaymentTransaction();


    }

    public function handle($data)
    {
        // TODO: Implement verify() method.
    }
}