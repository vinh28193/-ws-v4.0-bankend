<?php


namespace frontend\controllers;


use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\alepay\AlepayClient;

class TestController extends FrontendController
{

    public function actionTestMe(){
        echo PaymentService::createReturnUrl(42);
        die;
    }

    public function actionTestRsa(){

    }
    public function actionAlepay(){
        $alepay = new AlepayClient();
        echo "<pre>";
        var_dump($alepay->getInstallmentInfo(10000000,'VND'));
    }
}