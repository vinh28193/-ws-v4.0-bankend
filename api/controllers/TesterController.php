<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;

use common\promotion\PromotionForm;
use Yii;
use common\calculators\ConditionBuilder;
use common\calculators\Calculator;
use common\components\log\Logging;
use common\promotion\CheckoutPromotionForm;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\db\Expression;

class TesterController extends \yii\rest\Controller
{

    public function init()
    {
        parent::init();
        Yii::$app->getUser()->setIdentity(\common\models\User::findOne(1));
    }

    public function actionIndex()
    {

        var_dump(\Yii::$app->storeManager->getExchangeRate());
        die;
    }

    public function actionRate()
    {
        /** @var  $exRate  \common\components\ExchangeRate */
        $exRate = \Yii::$app->exRate;

        echo "rate from USD to VND: " . $exRate->usdToVnd(12, 0) . "\n";
        die;
    }

    public function actionLog()
    {
        echo "23";
        $log = Logging::create()->push('product', 'test', 'messager', ['abc' => 'def']);
        die;
    }

    public function actionCondition()
    {
        $string = '[{"conditions":[{"value":50,"key":"price","type":"int","operator":">="}],"value":5,"unit":"quantity","type":"P"},{"conditions":[{"value":50,"key":"price","type":"int","operator":"<"},{"value":5,"key":"quantity","type":"int","operator":">"}],"value":5,"unit":"quantity","type":"P"}]';
        $rules = json_decode($string, true);
        $target = new \stdClass();
        $target->getTotalOriginPrice = 99;
        $target->getShippingQuantity = 1;
        $cal = new Calculator();
        $cal->register($rules[0]);
        $cal->calculator($target);
        var_dump($cal->deception());

        die;
    }

    public function actionQuery()
    {

        $query = new Query();
        $condition = [
            'AND',
            ['IS NOT', 'trackingCode', new  Expression('NULL')],
            ['<', new Expression('LENGTH(trackingCode)'), 6]
//            [
//                'AND',
//                ['=', new Expression('trim(trackingCode)'), new  Expression('NULL')]
//            ]
        ];
        $builder = new QueryBuilder(\Yii::$app->db);
        $params = [];
        $sql = $builder->buildCondition($condition, $params);
        var_dump($sql, $params);
        die;
    }

    public function actionPromotion()
    {

        // remake params $_POST

        $posts = require dirname(dirname(__DIR__)).'/common/promotion/mock-post.php';
//        var_dump(strlen(json_encode($posts)));die;
        echo json_encode($posts);
        die;
    }
}