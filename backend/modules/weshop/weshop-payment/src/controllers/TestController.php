<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 14:51
 */

namespace weshop\payment\controllers;


use weshop\payment\Payment;

class TestController extends \yii\web\Controller
{

    public function actionIndex(){

        return $this->render('index');
    }
}