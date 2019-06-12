<?php


namespace frontend\modules\payment\providers\office;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;
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
        return new PaymentResponse(true, 'create payment success', $payment->transaction_code, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $payment->transaction_code, 'ok', $summitUrl, $payment->return_url, $payment->cancel_url);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */
        if (($transaction = PaymentTransaction::findOne(['transaction_code' => $data['code']])) === null) {
            return new PaymentResponse(false, 'Transaction không tồn tại');
        }
        $checkoutUrl = Url::to("/checkout/office/{$transaction->transaction_code}/success.html", true);
        return new PaymentResponse(true, 'check payment success', $transaction, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $data['code'], 'ok', $checkoutUrl);
    }
}