<?php


namespace frontend\controllers;


use common\components\cart\storage\MongodbCartStorage;
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
        var_dump($alepay->getInstallmentInfo(10000000.00,'VND'));
    }

    public function actionTestCart(){
        $model = new MongodbCartStorage;
        var_dump($model->GetAllShopingCarts());die;
    }
}