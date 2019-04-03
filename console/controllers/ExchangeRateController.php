<?php


namespace console\controllers;


use common\components\ExchangeRate;
use yii\console\Controller;

class ExchangeRateController extends Controller
{
    function actionUpdateRate(){
        echo "Bắt đầu lấy tỷ giá ........".PHP_EOL;
        $exc = new ExchangeRate();
        $exc->loadFormApi(true);
        $exc->loadFormVcb(true);
        echo PHP_EOL."................... Hoàn tất lấy tỷ giá".PHP_EOL;
    }
}