<?php


namespace frontend\modules\payment\providers\vietnam;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentProviderInterface;
use yii\helpers\Url;
use frontend\modules\payment\Payment;
use yii\base\BaseObject;

class WSVNOffice extends BaseObject implements PaymentProviderInterface
{

    /**
     * @param $payment Payment
     */

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
            'redirectUrl' => Url::to("/checkout/office/{$transaction->transaction_code}/success.html", true),
        ]);
    }
}