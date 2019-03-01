<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 11:12
 */

namespace weshop\payment\controllers;


use weshop\payment\Payment;

class MethodController extends \yii\web\Controller
{


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($name)
    {
        $payment = new Payment();
        $methods = $payment->getMethods();
        if (isset($methods[$name]) && ($method = $methods[$name]) !== null) {
            $activeMethod = $method;
        } else {
            $activeMethod = $methods[0];
        }
        return $this->render('view', [
            'methods' => $methods,
            'activeMethod' => $activeMethod,
            'activeName' => $name
        ]);
    }
}