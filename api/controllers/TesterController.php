<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


use common\calculators\ConditionBuilder;
use common\components\log\Logging;

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

    public function actionLog(){
        echo "23";
        $log = Logging::create()->push('product','test','messager',['abc' => 'def']);
        die;
    }

    public function actionCondition(){
        $builder = new ConditionBuilder();
        $condition = ['===','a','b'];
//        $condition = ['||',['===','A','B'],['===','C','D']];
        $condition =  (string) $builder->build($condition);
//        $sql = implode(' ', array_filter($condition));
        var_dump($condition);
        die;
    }
}