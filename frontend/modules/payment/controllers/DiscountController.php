<?php


namespace frontend\modules\payment\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\modules\payment\Payment;

class DiscountController extends BasePaymentController
{

    public function actionCheckPromotion()
    {

        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);
        $orders = ArrayHelper::remove($bodyParams, 'orders', []);
        if (empty($orders)) {
            return $this->response(false, 'empty');
        }
        $payment->setOrders($orders);
        $response = $payment->checkPromotion();
        return $this->response(true, 'check sucess', $response);
    }
}