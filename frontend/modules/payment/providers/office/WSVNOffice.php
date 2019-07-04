<?php


namespace frontend\modules\payment\providers\office;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentProviderInterface;
use frontend\modules\payment\PaymentResponse;
use frontend\modules\payment\PaymentService;
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
        return new PaymentResponse(true, 'create payment success','vnoffice', $payment->transaction_code, null,PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $payment->transaction_code, 'ok', $summitUrl, $payment->return_url, $payment->cancel_url);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */
        if (($transaction = PaymentService::findParentTransaction($data['code'])) === null) {
            return new PaymentResponse(false, 'Transaction không tồn tại','vnoffice');
        }
        $checkoutUrl = Url::to("/checkout/office/{$transaction->transaction_code}/success.html", true);
        return new PaymentResponse(true, 'check payment success','vnoffice', $transaction, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $data['code'], 'ok', $checkoutUrl);
    }
}