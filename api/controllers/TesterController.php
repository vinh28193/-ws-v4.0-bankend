<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


use common\components\TestObject;

class TesterController extends \yii\rest\Controller
{

    public function actionIndex(){
        $productComponent = new \common\products\ProductManager();
        $ebay = $productComponent->ebay->lookup(332800694983);
        var_dump($ebay);die;
    }
}