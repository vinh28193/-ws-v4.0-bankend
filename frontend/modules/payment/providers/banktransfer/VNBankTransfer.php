<?php


namespace frontend\modules\payment\providers\banktransfer;

use frontend\modules\payment\PaymentService;
use Yii;
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
        $queryParam = [
            'code' => $payment->transaction_code,
            'amount' => $payment->getTotalAmountDisplay(),
            'orders' => $payment->getOrderCodes()
        ];
        $queryParam = http_build_query($queryParam);
        $summitUrl .= "?$queryParam";
        return new PaymentResponse(true, 'create payment success', 'bankstransfervn', $payment->transaction_code, $payment->getOrderCodes(), PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $payment->transaction_code, 'ok', $summitUrl, $payment->return_url, $payment->cancel_url);
    }

    public function handle($data)
    {
        /** @var $transaction  PaymentTransaction */

        if (($transaction = PaymentService::findParentTransaction($data['code'])) === null) {
            return new PaymentResponse(false, 'Transaction không tồn tại', 'bankstransfervn');
        }
        $checkoutUrl = Url::to("/checkout/bank-transfer/{$transaction->transaction_code}/success.html", true);
        $checkoutUrl .= '?' . http_build_query($data);
        return new PaymentResponse(true, 'check payment success', 'bankstransfervn', $transaction, null, PaymentResponse::TYPE_REDIRECT, PaymentResponse::METHOD_GET, $data['code'], 'ok', $checkoutUrl);
    }
}