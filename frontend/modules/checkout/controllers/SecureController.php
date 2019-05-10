<?php


namespace frontend\modules\checkout\controllers;


class SecureController extends CheckoutController
{

    public $step = 1;

    public function actionIndex(){
        return $this->render('index');
    }
}