<?php


namespace frontend\modules\payment\providers\vietnam;

use common\components\ReponseData;
use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\base\BaseObject;
use yii\helpers\Url;

class BankTransfer extends BaseObject implements PaymentProviderInterface
{

    public function create(Payment $payment)
    {
        $summitUrl = $payment->return_url;
        $summitUrl .= '?code=' . $payment->transaction_code;
        return ReponseData::reponseArray(true, 'create payment success', [
            'code' => $payment->transaction_code,
            'token' => $payment->transaction_code,
            'checkoutUrl' => $summitUrl,
            'returnUrl' => $payment->return_url,
            'cancelUrl' => $payment->cancel_url,
            'method' => 'GET',
        ]);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */
        if (($transaction = PaymentTransaction::find()->where(['OR', ['transaction_code' => $data['code']], ['topup_transaction_code' => $data['code']]])->one()) === null) {
            return ReponseData::reponseMess(false, 'Transaction không tồn tại');
        }
        return ReponseData::reponseArray(true, 'check payment success', [
            'transaction' => $transaction,
            'redirectUrl' => Url::to("/checkout/bank-transfer/{$transaction->transaction_code}/success.html", true),
        ]);
    }
}