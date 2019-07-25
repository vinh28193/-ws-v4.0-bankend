<?php


namespace frontend\modules\payment\providers\nganluong\ver3_2;

use common\models\logs\PaymentGatewayLogs;
use common\models\PaymentTransaction;
use frontend\modules\payment\PaymentService;
use Yii;
use Exception;
use yii\base\BaseObject;
use common\models\PaymentProvider;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentResponse;
use frontend\modules\payment\PaymentProviderInterface;
use yii\helpers\ArrayHelper;

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

    public function actionReturnNicepay()
    {
        $merchant = 48;
        $start = microtime(true);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, $this->request);

        /** @var $paymentTransaction PaymentTransaction */
        $paymentTransaction = $res->paymentTransaction;
        $redirectUrl = PaymentService::createSuccessUrl($paymentTransaction->transaction_code);
        $failUrl = PaymentService::createCancelUrl($paymentTransaction->transaction_code);

        if ($res->success === false) {
            return $this->redirect($failUrl);
        }

        if ($res->checkoutUrl !== null) {
            $redirectUrl = $res->checkoutUrl;
        }
        if ($paymentTransaction->transaction_status === PaymentTransaction::TRANSACTION_STATUS_SUCCESS) {
            foreach ($paymentTransaction->childPaymentTransaction as $child) {
                $child->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;

                if (($order = $child->order) !== null) {
                    $order->total_paid_amount_local = $child->transaction_amount_local;
                    if ($order->current_status == Order::STATUS_SUPPORTED) {
                        $order->current_status = Order::STATUS_READY2PURCHASE;
                    }
                    $order->save(false);
                }
                $child->save(false);
            }

        }
        return $this->redirect($redirectUrl);

    }

    public function create(Payment $payment)
    {

        $this->getClient()->getParams()->set('order_code', $payment->transaction_code);
        $this->getClient()->getParams()->set('total_amount', $payment->getTotalAmountDisplay());
        $this->getClient()->getParams()->set('payment_method', NganluongHelper::getPaymentMethod($payment->payment_bank_code));
        $this->getClient()->getParams()->set('bank_code', NganluongHelper::replaceBankCode($payment->payment_bank_code));
        $this->getClient()->getParams()->set('cancel_url', $payment->cancel_url);
        $this->getClient()->getParams()->set('return_url', $payment->return_url);
        $this->getClient()->getParams()->set('notify_url', $payment->return_url);
        $this->getClient()->getParams()->set('total_amount', $payment->getTotalAmountDisplay());
        $this->getClient()->getParams()->set('order_description', "Thanh toan cho cac ma don: {$payment->getOrderCodes()}");
        $this->getClient()->getParams()->set('buyer_fullname', $payment->customer_name);
        $this->getClient()->getParams()->set('buyer_email', $payment->customer_email);
        $this->getClient()->getParams()->set('buyer_mobile', $payment->customer_phone);

        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $payment->transaction_code;

        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED;
        $logPaymentGateway->url = $this->getClient()->getAPIUrl();
        try {
            $mess = "Giao dịch thanh toán không thành công!";
            $success = true;
            $resp = $this->getClient()->SetExpressCheckout();
            $logPaymentGateway->request_content = $this->getClient()->getParams()->toArray();
            $logPaymentGateway->payment_method = $this->getClient()->getParams()->get('payment_method');
            $logPaymentGateway->payment_bank = $this->getClient()->getParams()->get('bank_code');
            $logPaymentGateway->amount = $this->getClient()->getParams()->get('total_amount');
            $logPaymentGateway->response_content = ($resp);
            $logPaymentGateway->response_time = date('Y-m-d H:i:s');
            $logPaymentGateway->create_time = date('Y-m-d H:i:s');
            $logPaymentGateway->store_id = 1;
            $logPaymentGateway->save(false);
            if ($resp == null || !is_array($resp) || !isset($resp['error_code']) || empty($resp['error_code']) || $resp['error_code'] != '00' || !isset($resp['token'])) {
                $mess = "Lỗi thanh toán trả về NL" . $resp['error_code'];
                if (isset($resp['description']) && $resp['description']) {
                    $mess = $resp['description'] . '.';
                }
                $success = false;
            }
            $returnUrl = $payment->return_url;
            $returnUrl .= "?token={$resp['token']}&order_code={$payment->transaction_code}";
            $cancelUrl = $payment->cancel_url;
            return new PaymentResponse($success, $mess, 'nganluong32', $payment->transaction_code, $payment->getOrderCodes(), PaymentResponse::TYPE_POPUP, PaymentResponse::METHOD_QRCODE, $resp['token'], $resp['error_code'], isset($resp['qr_data']) ? $resp['qr_data'] : '', $returnUrl, $cancelUrl);

        } catch (Exception $exception) {
            $logPaymentGateway->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
            $logPaymentGateway->save(false);
            return new PaymentResponse(false, 'Check payment thất bại', 'nganluong32');
        }

    }

    public function handle($data)
    {
        $orderCode = $data['order_code'];
        $token = $data['token'];
        $logPaymentGateway = new PaymentGatewayLogs();
        $logPaymentGateway->transaction_code_ws = $orderCode;
        $logPaymentGateway->request_content = $data;
        $logPaymentGateway->transaction_code_request = $token;
        $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK;
        $logPaymentGateway->url = $this->getClient()->getAPIUrl();

        try {
            $transaction = PaymentService::findParentTransaction($orderCode);
            if ($transaction === null && ($transaction = PaymentService::findChildTransaction($orderCode)) === null) {
                $logPaymentGateway->request_content = "Không tìm thấy transaction ở cả 2 bảng transaction!";
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
                $logPaymentGateway->save(false);
                return new PaymentResponse(false, 'Transaction không tồn tại','nganluong');
            }
            $resp = $this->getClient()->GetTransactionDetail($token);

            $logPaymentGateway->request_content = $this->getClient()->getParams()->toArray();
            $logPaymentGateway->response_content = ($resp);
            $logPaymentGateway->response_time = date('Y-m-d H:i:s');
            $logPaymentGateway->create_time = date('Y-m-d H:i:s');
            $logPaymentGateway->store_id = 1;
            $logPaymentGateway->save(false);
            if ($resp['error_code'] != '00') {
                return new PaymentResponse(false, 'failed', 'nganluong32');
            }
            $transaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
            $transaction->save(false);
            return new PaymentResponse(true, 'Giao dịch thanh toán thành công!', 'nganluong32', $transaction);
        } catch (Exception $exception) {
            $logPaymentGateway->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CALLBACK_FAIL;
            $logPaymentGateway->save(false);
            return new PaymentResponse(false, 'Check payment thất bại', 'nganluong32');
        }
    }

}
