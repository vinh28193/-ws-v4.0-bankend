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
        $success = false;
        $message = 'no found';
        $data = [
            'calculator' => 'installment',
            'origin' => $this->storeManager->showMoney($payment->getTotalAmountDisplay()),
            'methods' => []
        ];
        if ($provider === 44) {
            $success = true;
            $message = 'success';
            $data = ArrayHelper::merge($data, [
                'calculator' => 'alepay',
                'methods' => $this->getAlapayCalculator($payment)
            ]);
        }
        return $this->response($success, $message, $data);
    }

    /**
     * @param $payment Payment
     * @return array
     */
    private function getAlapayCalculator($payment)
    {
        $alepay = new AlepayClient();
        $storeManager = $this->storeManager;
        $rs = $alepay->getInstallmentInfo($payment->getTotalAmountDisplay(), 'VND');
        if ($rs['success']) {
            $data = Json::decode($rs['data']);
            $banks = [];
            foreach ($data as $key => $item) {
                $item['bankIcon'] = PaymentService::getInstallmentBankIcon($item['bankCode']);
                $methods = [];
                foreach ($item['paymentMethods'] as $i => $method) {
                    $method['methodIcon'] = PaymentService::getInstallmentMethodIcon($method['paymentMethod']);
                    $periods = [];
                    foreach ($method['periods'] as $period) {
                        $period['amountByMonth'] = $storeManager->showMoney($period['amountByMonth'], $period['currency']);
                        $period['amountFee'] = $storeManager->showMoney($period['amountFee'], $period['currency']);
                        $period['amountFinal'] = $storeManager->showMoney($period['amountFinal'], $period['currency']);
                        $periods[] = $period;
                    }
                    $method['periods'] = $periods;
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