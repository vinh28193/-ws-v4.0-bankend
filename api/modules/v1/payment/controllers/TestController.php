<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 14:51
 */

namespace api\modules\v1\payment\controllers;


use api\modules\v1\payment\Payment;

class TestController extends \yii\web\Controller
{

    public function actionIndex(){

        $payment = new Payment();
        $payment->getProviders();

        var_dump($payment);
        die;
    }
}