<?php


namespace frontend\modules\payment\controllers;

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
            'content' => ''
        ];
        if ($provider === 44) {
            $success = true;
            $message = 'success';
            $data = ArrayHelper::merge($data, [
                'content' => $this->getAlapayCalculator($payment)
            ]);
        }
        return $this->response($success, $message, $data);
    }

    /**
     * @param $payment
     * @return string
     */
    private function getAlapayCalculator($payment)
    {
        $alepay = new AlepayClient();
        $rs = $alepay->getInstallmentInfo($payment->total_amount_display, 'VND');
        if ($rs['success']) {
            return $this->renderAjax('alepay', [
                'results' => Json::decode($rs['data'], true)
            ]);
        }
        return $this->renderAjax('bank');
    }
}