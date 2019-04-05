<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


use common\calculators\ConditionBuilder;
use common\calculators\Calculator;
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

        $string = '[{"conditions":[{"value":50,"key":"price","type":"int","operator":">="}],"value":5,"unit":"quantity","type":"P"},{"conditions":[{"value":50,"key":"price","type":"int","operator":"<"},{"value":5,"key":"quantity","type":"int","operator":">"}],"value":5,"unit":"quantity","type":"P"}]';
        $rules = json_decode($string,true);
        $target = new \stdClass();
        $target->price = 99;
        $target->quantity = 1;
        $cal = new Calculator();
        $cal->register($rules[0]);
        $cal->calculator($target);
        var_dump($cal->deception());

        die;
    }
}