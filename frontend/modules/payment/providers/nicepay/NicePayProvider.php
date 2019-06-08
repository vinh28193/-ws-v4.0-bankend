<?php

namespace frontend\modules\payment\providers\nicepay;

use Exception;
use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
        // Populate data
        //$this->getClient()->getData()->set('instmntMon', '1');
        //$this->getClient()->getData()->set('instmntType', '0');
        //$this->getClient()->getData()->set('vat', '0');
        //$this->getClient()->getData()->set('fee', '0');
        $this->getClient()->getData()->set('notaxAmt', '0');
        if ($this->getClient()->getData()->get('fee') == "") {
            $this->getClient()->getData()->setDefault('fee', '0');
        }
        if ($this->getClient()->getData()->get('vat') == "") {
            $this->getClient()->getData()->setDefault('vat', '0');
        }
        if ($this->getClient()->getData()->get('cartData') == "") {
            $this->getClient()->getData()->setDefault('cartData', '{}');
        }

        // Send Request
        if (($response = $this->getClient()->chargeCard()) === false) {
            return $response;
        }
        return false;
    }

    public function checkPaymentStatus($tXid, $referenceNo, $amt)
    {
        // Populate data
        $this->getClient()->getData()->setDefault('tXid', $tXid);
        $this->getClient()->getData()->setDefault('referenceNo', $referenceNo);
        $this->getClient()->getData()->setDefault('amt', $amt);

        // Send Request
        if (($response = $this->getClient()->checkPaymentStatus()) === false) {
            return $response;
        }
        return false;
    }

    // Cancel VA (VA can be canceled only if VA status is not paid)
    public function cancelVA($tXid, $amt)
    {

        // Populate data
        $this->getClient()->getData()->set('tXid', $tXid);
        $this->getClient()->getData()->set('amt', $amt);

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
        if ($payment->payment_method == NicePayUtils::PAYMENT_METHOD && $payment->total_amount < NicePayUtils::AMOUNT_REQUIRED) {
            return ReponseData::reponseArray(false, "Amount lesser than installment require");
        }
        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;
        try {
            if ($payment->instalment_type == 2 && !empty($payment->installment_bank) && !empty($payment->installment_method)) {
                $this->getClient()->getData()->set('payMethod', '01');
                $this->getClient()->getData()->set('instmntMon', $payment->installment_month);
                $this->getClient()->getData()->set('instmntType', $payment->instalment_type);
                if (($response = $this->chargeCard()) === false) {
                    $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                    $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                    $logPaymentGateway->response_content = $this->getClient()->getErrorMsg();
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(false, $this->getClient()->getErrorMsg());
                }
                if (isset($response['data']) && ($data = $response['data']) !== null && !empty($data) && isset($data['resultCd']) && $data['resultCd'] == '0000') {
                    $checkoutUrl = $data['requestURL'] . '?tXid=' . $data['tXid'];
                    $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                    $logPaymentGateway->response_content = $response;
                    $logPaymentGateway->url = $checkoutUrl;
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(true, 'Success', [
                        'code' => $payment->transaction_code,
                        'status' => $data['resultCd'],
                        'token' => $data['tXid'],
                        'checkoutUrl' => $checkoutUrl,
                        'method' => 'GET',
                    ]);
                }
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                $logPaymentGateway->response_content = $response;
                $logPaymentGateway->save(false);
                return ReponseData::reponseArray(false, '"Lỗi cổng thanh toán');

            } elseif ($payment->instalment_type == 1) {
                $this->getClient()->getData()->set('payMethod', '02');
                $this->getClient()->getData()->set('bankCd', $bankCd);
                $this->getClient()->getData()->set('vacctValidDt', $vaExpiryDate); // Set VA expiry date example: +1 day
                $this->getClient()->getData()->set('vacctValidTm', date('His')); // Set VA Expiry Time
                if (($response = $this->requestVA()) === false) {
                    $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                    $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                    $logPaymentGateway->response_content = $this->getClient()->getErrorMsg();
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(false, $this->getClient()->getErrorMsg());
                }
                if (isset($response['resultCd']) && $response['resultCd'] == '0000') {
                    $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                    $logPaymentGateway->response_content = $response;
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(true, 'Success');
                }
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                $logPaymentGateway->response_content = $response;
                $logPaymentGateway->save(false);
                return ReponseData::reponseArray(false, 'Lỗi cổng thanh toán');
            } else {
                $this->getClient()->getData()->set('payMethod', '02');
                $this->getClient()->getData()->set('bankCd', $bankCd);
                $this->getClient()->getData()->set('vacctValidDt', $vaExpiryDate); // Set VA expiry date example: +1 day
                $this->getClient()->getData()->set('vacctValidTm', date('His')); // Set VA Expiry Time
                if (($response = $this->requestVA()) === false) {
                    $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                    $logPaymentGateway->response_content = $this->getClient()->getErrorMsg();
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(false, $this->getClient()->getErrorMsg());
                }
                if (isset($response['resultCd']) && $response['resultCd'] == '0000') {
                    $checkoutUrl = Url::to(['/payment/nicepay/success', $response]);
                    $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                    $logPaymentGateway->response_content = $response;
                    $logPaymentGateway->url = $checkoutUrl;
                    $logPaymentGateway->save(false);
                    return ReponseData::reponseArray(true, 'Nicepay banktranfer success', [
                        'code' => $payment->transaction_code,
                        'status' => $response['resultCd'],
                        'token' => $response['transDt'],
                        'checkoutUrl' => $checkoutUrl,
                        'method' => 'GET',
                    ]);
                }
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                $logPaymentGateway->request_content = $this->getClient()->getData()->toArray();
                $logPaymentGateway->response_content = $response;
                $logPaymentGateway->save(false);
                return ReponseData::reponseArray(false, 'Lỗi cổng thanh toán');
            }
        } catch (Exception $exception) {
            $logPaymentGateway->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
            $logPaymentGateway->save(false);
        }


    }

    public function handle($data)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->request_content = $data;
        $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logCallback->transaction_code_request = "NICE PAY CALLBACK";
        $logCallback->store_id = 1;

        $tXid = $this->getNotifications()->get('tXid');
        $referenceNo = $this->getNotifications()->get('referenceNo');
        $amt = $this->getNotifications()->get('amt');


        if ($referenceNo === null) {
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->request_content = "Missing parameter referenceNo";
            $logCallback->response_content = $this->getNotifications()->toArray();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseMess(false, 'Missing parameter referenceNo');
        }
        if (($transaction = PaymentTransaction::findOne(['transaction_code' => $referenceNo])) === null) {
            $logCallback->request_content = "Không tìm thấy transaction";
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseMess(false, 'Transaction not found');
        }
        try {
            if (($response = $this->checkPaymentStatus($tXid, $referenceNo, $amt)) === false) {
                $logCallback->response_content = $this->getClient()->getErrorMsg();
                $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logCallback->save(false);
                return ReponseData::reponseArray(false, $this->getClient()->getErrorMsg());
            }

            if (isset($response['status']) && $response['status'] == 0) {
                $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $transaction->save();
                $logCallback->response_content = $response;
                $logCallback->save(false);
                return ReponseData::reponseArray(true, 'Success');
            } else {
                $this->isInstallment = true;
                if (($response = $this->checkPaymentStatus($tXid, $referenceNo, $amt)) === false) {
                    $logCallback->response_content = $this->getClient()->getErrorMsg();
                    $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                    $logCallback->save(false);
                    return ReponseData::reponseArray(false, $this->getClient()->getErrorMsg());
                }
                if (isset($response['resultCd']) && isset($response['resultMsg']) && $response['resultCd'] == '0000' && $response['resultMsg'] == 'paid') {
                    $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                    $transaction->save(false);
                    $logCallback->response_content = $response;
                    $logCallback->save(false);
                    return ReponseData::reponseArray(true, 'Installment Success');
                }
                $logCallback->response_content = $response;
                $logCallback->save(false);
                return ReponseData::reponseArray(false, 'Failed');
            }

        } catch (Exception $e) {
            $logCallback->response_content = $e->getMessage() . " \n " . $e->getFile() . " \n " . $e->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseArray(false, 'Call back fail');
        }
    }
}