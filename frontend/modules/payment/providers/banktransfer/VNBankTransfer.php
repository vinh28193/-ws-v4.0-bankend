<?php


namespace frontend\modules\payment\providers\banktransfer;

use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;
use yii\base\BaseObject;
use yii\helpers\Url;

class VNBankTransfer extends BaseObject implements PaymentProviderInterface
{

    public function create(Payment $payment)
    {
        $summitUrl = $payment->return_url;
        $summitUrl .= '?code=' . $payment->transaction_code;
        return new PaymentResponse(true, 'create payment success', $payment->transaction_code, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $summitUrl, $payment->return_url, $payment->cancel_url);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */
        if (($transaction = PaymentTransaction::find()->where(['OR', ['transaction_code' => $data['code']], ['topup_transaction_code' => $data['code']]])->one()) === null) {
            return new PaymentResponse(false, 'Transaction không tồn tại');
        }
        $checkoutUrl = Url::to("/checkout/bank-transfer/{$transaction->transaction_code}/success.html", true);
        return new PaymentResponse(true, 'check payment success', $transaction, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $data['code'], $checkoutUrl);
    }
}