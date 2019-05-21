<?php


namespace frontend\controllers;


use frontend\modules\payment\PaymentService;

class TestController extends FrontendController
{

    public function actionTestMe(){
        echo PaymentService::createReturnUrl(42);
        die;
    }
}