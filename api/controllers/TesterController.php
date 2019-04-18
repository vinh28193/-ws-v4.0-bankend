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

        var_dump(\Yii::$app->storeManager->getExchangeRate());
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
        $string = '[{"conditions":[{"value":50,"key":"price","type":"int","operator":">="}],"value":5,"unit":"quantity","type":"P"},{"conditions":[{"value":50,"key":"price","type":"int","operator":"<"},{"value":5,"key":"quantity","type":"int","operator":">"}],"value":5,"unit":"quantity","type":"P"}]';
        $rules = json_decode($string,true);
        $target = new \stdClass();
        $target->getTotalOriginPrice = 99;
        $target->getShippingQuantity = 1;
        $cal = new Calculator();
        $cal->register($rules[0]);
        $cal->calculator($target);
        var_dump($cal->deception());

        die;
    }

    public function actionNow(){
        $f =\Yii::$app->getFormatter();
        echo "now {$f->asDatetime('now')}\n";
        echo "<br>";
        echo "+3 days {$f->asDatetime('now + 300 seconds')}\n";
        $dateTime = new \DateTime('now');

        echo "day week {$dateTime->format('U')}\n";
//        echo "day week {$f->asDatetime(1340692034,'l')}";
//        $datee = getFormatter()->asDatetime(1340692034,'l');
//        echo "test {$datee}";
        die;
    }
}