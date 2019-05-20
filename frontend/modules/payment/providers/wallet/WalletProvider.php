<?php


namespace frontend\modules\payment\providers\wallet;

use common\components\ReponseData;
use common\models\logs\PaymentGatewayLogs;
use yii\base\BaseObject;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use yii\helpers\Url;

class WalletProvider extends BaseObject implements PaymentProviderInterface
{
    const WALLET_CHECKOUT_OTP = 'otp-verify';

    public function create(Payment $payment)
    {
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->type = PaymentGatewayLogs::TYPE_CHECK_CREATED;
        $logCallback->transaction_code_request = "WALLET CREATE PAYMENT";
        $logCallback->store_id = 1;
        try {
            $params = $this->createParams($payment);
            $walletService = new WalletService($params);

            $logPaymentGateway = new PaymentGatewayLogs();
            $logPaymentGateway->transaction_code_ws = $payment->transaction_code;
            $logPaymentGateway->request_content = $params;
            $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CHECK_CREATED;

            $response = $walletService->createPaymentTransaction();
            if ($response['success'] === true && $response['code'] !== '0000') {
                $success = false;
                $mess = $response['message'];
                $logPaymentGateway->response_content = ($response);
                $logPaymentGateway->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
                $logCallback->save(false);
                return ReponseData::reponseArray($success, $mess);
            }
            $submitUrl = $this->getCheckoutOtpUrl($response['data']);
            $request_content['api'] = $submitUrl;
            $request_content['form'] = $params;
            $data = [
                'code' => $payment->transaction_code,
                'token' => $response['data'],
                'checkoutUrl' => $submitUrl,
                'returnUrl' => $payment->return_url,
                'cancelUrl' => $payment->cancel_url,
                'provider' => 'WALLET',
                'method' => 'POPUP',
            ];
            $logCallback->request_content = $request_content;
            $logCallback->response_content = $response;
            $logCallback->type = PaymentGatewayLogs::TYPE_CREATED;
            $logCallback->save(false);
            return ReponseData::reponseArray(true, "Giao dịch thanh toán thành công!", $data);
        } catch (\Exception $exception) {
            $logCallback->request_content = $exception->getMessage() . " \n " . $exception->getFile() . " \n " . $exception->getTraceAsString();
            $logCallback->type = PaymentGatewayLogs::TYPE_CREATED_FAIL;
            $logCallback->save(false);
            return ReponseData::reponseArray(false, 'thất bại');
        }

    }

    /**
     * @param $payment Payment
     * @return array
     */
    private function createParams($payment)
    {
        return [
            'merchant_id' => WalletService::MERCHANT_IP_PRO,
            'transaction_code' => $payment->transaction_code,
            'payment_method' => $payment->payment_method_name,
            'payment_provider' => $payment->payment_provider_name,
            'bank_code' => $payment->payment_bank_code,
            'total_amount' => $payment->total_amount - $payment->total_discount_amount,
            'otp_type' => $payment->otp_verify_method
        ];
    }

    public function handle($data)
    {
        // TODO: Implement verify() method.
    }

    public function getCheckoutOtpUrl($code)
    {
        return Url::to("/otp/{$code}/verify.html", true);
    }
}