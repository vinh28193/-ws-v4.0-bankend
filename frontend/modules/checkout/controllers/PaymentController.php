<?php


namespace frontend\modules\checkout\controllers;


class PaymentController extends CheckoutController
{
    public $step = 3;

    public function actionIndex(){
        return $this->render('index');
    }
}