<?php


namespace frontend\modules\payment\controllers;

use frontend\modules\payment\PaymentService;
use Yii;
use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\alepay\AlepayClient;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class InstallmentController extends BasePaymentController
{

    public function actionCalculator($provider)
    {
        $provider = (int)$provider;
        $params = $this->request->bodyParams;
        $payment = new Payment($params);
        $promotion = $payment->checkPromotion();
        $success = false;
        $message = 'no found';
        $data = [
            'promotion' => $promotion,
            'methods' => ''
        ];
        if ($provider === 44) {
            $success = true;
            $message = 'success';
            $data = ArrayHelper::merge($data, [
                'methods' => $this->getAlapayCalculator($payment)
            ]);
        }
        return $this->response($success, $message, $data);
    }

    /**
     * @param $payment
     * @return array
     */
    private function getAlapayCalculator($payment)
    {
        $alepay = new AlepayClient();
        $rs = $alepay->getInstallmentInfo($payment->total_amount_display, 'VND');
        if ($rs['success']) {
            $data = Json::decode($rs['data']);
            $banks = [];
            foreach ($data as $key => $item) {
                $item['bankIcon'] = PaymentService::getInstallmentBankIcon($item['bankCode']);
                $methods = [];
                foreach ($item['paymentMethods'] as $i => $method) {
                    $method['methodIcon'] = PaymentService::getInstallmentMethodIcon($method['paymentMethod']);
                    $methods[] = $method;
                }
                $item['paymentMethods'] = $methods;
                $banks[] = $item;
            }
            return $banks;
        }
        return [];
    }
}