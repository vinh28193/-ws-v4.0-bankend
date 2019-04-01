<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


class TesterController extends \yii\rest\Controller
{

    public function actionIndex()
    {
        $productComponent = new \common\products\ProductManager();
        $ebay = $productComponent->ebay->lookup(332800694983);
        var_dump($ebay);
        die;
    }

    public function actionRate()
    {
        /** @var  $exRate  \common\components\ExchangeRate */
        $exRate = \Yii::$app->exRate;

        echo "rate from USD to VND: " . $exRate->usdToVnd(12, 0) ."\n";
        die;
    }
}