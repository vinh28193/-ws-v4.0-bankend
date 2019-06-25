<?php

namespace frontend\modules\payment\providers\cod;

use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentResponse;
use yii\base\BaseObject;
use frontend\modules\payment\PaymentProviderInterface;
use yii\helpers\Url;

class CodProvider extends BaseObject implements PaymentProviderInterface
{

    public function create(Payment $payment)
    {
        $summitUrl = $payment->return_url;
        $summitUrl .= '?code=' . $payment->transaction_code;
        return new PaymentResponse(true, 'create payment success','cod', $payment->transaction_code, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $payment->transaction_code, 'ok', $summitUrl, $payment->return_url, $payment->cancel_url);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */
        if (($transaction = PaymentTransaction::findOne(['transaction_code' => $data['code']])) === null) {
            return new PaymentResponse(false, 'Transaction không tồn tại','cod');
        }
        $checkoutUrl = Url::to("/checkout/cod/{$transaction->transaction_code}/success.html", true);
        return new PaymentResponse(true, 'check payment success','cod', $transaction, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $data['code'], 'ok', $checkoutUrl);
    }
}