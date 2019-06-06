<?php

namespace frontend\modules\payment\providers\nicepay;

use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\base\BaseObject;

class NicePayProvider extends BaseObject implements PaymentProviderInterface
{
    const NICEPAY_PROGRAM = 'NicepayLite';
    const NICEPAY_VERSION = '1.11';
    const NICEPAY_BUILDDATE = '20160309';
    const NICEPAY_IMID = 'WESHOP1122';
    const NICEPAY_MERCHANT_KEY = 'vnMmWGv8+Ao7P9iI3G9IdwQ1cefHOrryIa4ELPBTd/uTCXdW4R+vTfABNuM6ofeiokxG976f9Mh9YywR7WLEJQ==';
    const NICEPAY_IMID_INSTALLMENT = 'WESHOPINS2';
    const NICEPAY_MERCHANT_KEY_INSTALLMENT = 'p9tK0wDh/sodB9caI0eN/ZNNjgPw8qwBykqR7rlO/GAAxlLMY5EUbTvon6j83Iwwa5DDefC0V+kj//cS5Hikjw==';


    public $isInstallment = false;
    public $iMid = self::NICEPAY_IMID;
    public $iMidInstallment = self::NICEPAY_IMID_INSTALLMENT;
    public $merchantKey = self::NICEPAY_MERCHANT_KEY;
    public $merchantKeyInstallment = self::NICEPAY_MERCHANT_KEY_INSTALLMENT;


    /**
     * @var NicePayClient
     */
    private $_client;

    /**
     * @return NicePayClient
     */
    public function getClient()
    {
        if (!is_object($this->_client)) {
            $this->_client = new NicePayClient();
        }
        return $this->_client;
    }

    /**
     * @var NotificationCollection
     */
    private $_notifications;

    /**
     * @return NotificationCollection
     */
    public function getNotifications()
    {
        if ($this->_notifications === null) {
            $this->_notifications = new NotificationCollection();
            foreach ($_REQUEST as $name => $value) {
                $this->_notifications->set($name, $value);
            }
        }
        return $this->_notifications;
    }

    /**
     * @return string
     */
    public function merchantTokenInstallment()
    {
        // Concatenate(iMid + referenceNo + amt + merchantKey)
        return NicePayUtils::hashData($this->getClient()->getData()->get('iMid') .
            $this->getClient()->getData()->get('referenceNo') .
            $this->getClient()->getData()->get('amt') .
            $this->merchantKeyInstallment
        );
    }

    /**
     * @return string
     */
    public function merchantTokenC()
    {
        // Concatenate(iMid + referenceNo + amt + merchantKey)
        return NicePayUtils::hashData($this->getClient()->getData()->get('iMid') .
            $this->getClient()->getData()->get('tXid') .
            $this->getClient()->getData()->get('amt') .
            $this->merchantKey
        );
    }

    public function merchantToken()
    {
        if ($this->isInstallment) {
            NicePayUtils::hashData($this->iMidInstallment .
                $this->getClient()->getData()->get('tXid') .
                $this->getClient()->getData()->get('amt') .
                $this->merchantKeyInstallment
            );
        }
        //Concatenate(iMid + referenceNo + amt + merchantKey)
        return NicePayUtils::hashData($this->getClient()->getData()->get('iMid') .
            $this->getClient()->getData()->get('referenceNo') .
            $this->getClient()->getData()->get('amt') .
            $this->merchantKey
        );
    }

    // Request VA
    public function requestVA()
    {
        $client = $this->getClient();
        // Populate data
        $client->getData()->set('instmntMon', '1');
        $client->getData()->set('instmntType', '1');
        $client->getData()->set('vat', '0');
        $client->getData()->set('fee', '0');
        $client->getData()->set('notaxAmt', '0');
        if ($this->get('cartData') == "") {
            $client->getData()->set('cartData', '{}');
        }

        if (($response = $this->getClient()->requestVA()) === false) {
            return $response;
        }
        return false;
    }

    // Charge Credit Card
    public function chargeCard()
    {
        $client = $this->getClient();
        // Populate data
        //$client->getData()->set('instmntMon', '1');
        //$client->getData()->set('instmntType', '0');
        //$client->getData()->set('vat', '0');
        //$client->getData()->set('fee', '0');
        $client->getData()->set('notaxAmt', '0');
        if ($client->getData()->get('fee') == "") {
            $client->getData()->setDefault('fee', '0');
        }
        if ($client->getData()->get('vat') == "") {
            $client->getData()->setDefault('vat', '0');
        }
        if ($client->getData()->get('cartData') == "") {
            $client->getData()->setDefault('cartData', '{}');
        }

        // Send Request
        if (($response = $this->getClient()->chargeCard()) === false) {
            return $response;
        }
        return false;
    }

    public function checkPaymentStatus($tXid, $referenceNo, $amt)
    {
        $client = $this->getClient();
        // Populate data
        $client->getData()->set('tXid', $tXid);
        $client->getData()->set('referenceNo', $referenceNo);
        $client->getData()->set('amt', $amt);

        // Send Request
        if (($response = $this->getClient()->checkPaymentStatus()) === false) {
            return $response;
        }
        return false;
    }

    // Cancel VA (VA can be canceled only if VA status is not paid)
    public function cancelVA($tXid, $amt)
    {
        $client = $this->getClient();

        // Populate data
        $client->getData()->set('tXid', $tXid);
        $client->getData()->set('amt', $amt);

        // Send Request
        if (($response = $this->getClient()->requestVa()) === false) {
            return $response;
        }
        return false;
    }

    public function create(Payment $payment)
    {

        $bankCd = !empty($payment->payment_bank_code) ? $payment->payment_bank_code : '';
        $dateNow = date('Ymd');
        $vaExpiryDate = date('Ymd', strtotime($dateNow . ' +1 day')); // Set VA expiry date +1 day (optional)
        // Populate Mandatory parameters to send
        $this->getClient()->getData()->set('currency', 'IDR');
        $this->getClient()->getData()->set('amt', round($payment->total_amount)); // Total gross amount
        $this->getClient()->getData()->set('dbProcessUrl', $payment->return_url); // Total gross amount
        $this->getClient()->getData()->set('referenceNo', $payment->transaction_code);
        $this->getClient()->getData()->set('callBackUrl', $payment->cancel_url);
        $this->getClient()->getData()->set('description', 'Payment of Invoice No ' . $this->getClient()->getData()->get('referenceNo')); // Transaction description
        $this->getClient()->getData()->set('billingNm', $payment->customer_name); // Customer name
        $this->getClient()->getData()->set('billingPhone', $payment->customer_phone); // Customer phone number
        $this->getClient()->getData()->set('billingEmail', $payment->customer_email); //
        $this->getClient()->getData()->set('billingAddr', $payment->customer_address);
        $this->getClient()->getData()->set('billingCity', $payment->customer_district);
        $this->getClient()->getData()->set('billingState', $payment->customer_city);
        $this->getClient()->getData()->set('billingPostCd', $payment->customer_postcode);
        $this->getClient()->getData()->set('billingCountry', $payment->customer_country);
        $this->getClient()->getData()->set('deliveryNm', $payment->customer_name); // Delivery name
        $this->getClient()->getData()->set('deliveryPhone', $payment->customer_phone);
        $this->getClient()->getData()->set('deliveryEmail', $payment->customer_email);
        $this->getClient()->getData()->set('deliveryAddr', $payment->customer_address);
        $this->getClient()->getData()->set('deliveryCity', $payment->customer_district);
        $this->getClient()->getData()->set('deliveryState', $payment->customer_city);
        $this->getClient()->getData()->set('deliveryPostCd', $payment->customer_postcode);
        $this->getClient()->getData()->set('deliveryCountry', $payment->customer_country);
    }

    public function handle($data)
    {
        // TODO: Implement handle() method.
    }
}