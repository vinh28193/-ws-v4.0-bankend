<?php


namespace frontend\modules\checkout\controllers;

use Yii;
use yii\web\Response;
use common\payment\Payment;

class DiscountController extends CheckoutController
{


    public function actionCheckPromotion(){
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);
        $response = $payment->checkPromotion(true);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}