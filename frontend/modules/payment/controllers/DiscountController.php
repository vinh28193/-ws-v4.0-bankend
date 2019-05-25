<?php


namespace frontend\modules\payment\controllers;

use Yii;
use yii\web\Response;
use frontend\modules\payment\Payment;

class DiscountController extends BasePaymentController
{


    public function actionCheckPromotion(){
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);

        $response = $payment->checkPromotion();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}