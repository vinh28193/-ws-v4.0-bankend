<?php


namespace frontend\modules\checkout\controllers;


class ReceiverController extends CheckoutController
{

    public $step = 2;

    public function actionIndex(){
        return $this->render('index');
    }
}