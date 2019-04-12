<?php

namespace common\tests\unit\promotion;

use common\promotion\PromotionConditionConfig;
use common\tests\UnitTestCase;
use common\promotion\PromotionRequest;
use common\promotion\PromotionCondition;
use yii\helpers\ArrayHelper;

class PromotionConditionTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function testCheckConditionRecursive()
    {

        $conditions = [
            'amount' => [
                'name' => 'amount',
                'value' => 999,
                'operator' => '<',
                'type_cast' => 'integer'
            ]
        ];

        foreach ($conditions as $name => $params) {
            $this->specify("check condition $name", function () use ($params) {
                $condition = new PromotionCondition([
                    'name' => $params['name'],
                    'value' => $params['value']
                ]);
                $config = new PromotionConditionConfig([
                    'name' => $params['name'],
                    'operator' => $params['operator'],
                    'type_cast' => $params['type_cast']
                ]);
                $condition->populateRelation('promotionConditionConfig', $config);
                $this->specify('i want check case pass condition', function () use ($condition) {
                    $request = $this->make(PromotionRequest::className(), [
                        $condition->name => 998
                    ]);
                    verify($condition->checkConditionRecursive($request))->true();
                });

                $this->specify('i want check case not  pass condition', function () use ($condition) {
                    $request = $this->make(PromotionRequest::className(), [
                        $condition->name => 1000
                    ]);
                    verify($condition->checkConditionRecursive($request))->false();
                });
            });
        }
        $conditionConfig = new PromotionConditionConfig([
            'name' => 'amount'
        ]);

    }
}