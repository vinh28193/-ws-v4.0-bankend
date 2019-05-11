<?php


namespace frontend\modules\checkout\controllers;


class ShippingController extends CheckoutController
{

    public function actionIndex(){
        return $this->render('index');
    }

}