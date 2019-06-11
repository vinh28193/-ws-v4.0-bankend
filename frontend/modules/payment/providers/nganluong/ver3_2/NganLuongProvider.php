<?php


namespace frontend\modules\payment\providers\nganluong\ver3_2;

use frontend\modules\payment\providers\nganluong\NganluongHelper;
use Yii;
use Exception;
use yii\base\BaseObject;
use common\models\PaymentProvider;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentResponse;
use frontend\modules\payment\PaymentProviderInterface;

class NganLuongProvider extends BaseObject implements PaymentProviderInterface
{

    /**
     * @var NganLuongClient;
     */
    private $_client;

    /**
     * @return NganLuongClient
     */
    public function getClient()
    {
        if (!is_object($this->_client)) {
            $this->_client = new NganLuongClient();
        }
        return $this->_client;
    }

    public function create(Payment $payment)
    {

        $this->getClient()->getParams()->set('order_code', $payment->transaction_code);
        $this->getClient()->getParams()->set('total_amount', $payment->getTotalAmountDisplay());
        $this->getClient()->getParams()->set('payment_method', NganluongHelper::getPaymentMethod($payment->payment_bank_code));
        $this->getClient()->getParams()->set('bank_code', NganluongHelper::replaceBankCode($payment->payment_bank_code));
        $this->getClient()->getParams()->set('cancel_url', $payment->cancel_url);
        $this->getClient()->getParams()->set('return_url', $payment->cancel_url);
        $this->getClient()->getParams()->set('order_code', $payment->getTotalAmountDisplay());
        $this->getClient()->getParams()->set('order_code', $payment->getTotalAmountDisplay());
        $this->getClient()->getParams()->set('order_description', '');
        $this->getClient()->getParams()->set('buyer_fullname',$payment->customer_name);
        $this->getClient()->getParams()->set('buyer_email',$payment->customer_email);
        $this->getClient()->getParams()->set('buyer_mobile',$payment->customer_phone);
        $this->getClient()->getParams()->set('card_number',$payment->bank_account);
        $this->getClient()->getParams()->set('card_fullname',$payment->bank_name);
        $this->getClient()->getParams()->set('card_month',$payment->expired_month);
        $this->getClient()->getParams()->set('card_year',$payment->expired_year);

    }

    public function handle($data)
    {
        // TODO: Implement handle() method.
    }

}