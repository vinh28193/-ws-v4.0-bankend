<?php

namespace common\tests\unit\promotion;

use common\promotion\PromotionConditionConfig;
use common\tests\UnitTestCase;
use common\promotion\PromotionRequest;
use common\promotion\PromotionCondition;
use yii\helpers\ArrayHelper;
use common\tests\stubs\PromotionRequestParam;

class PromotionConditionTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function testCheckConditionRecursive()
    {

        $testCases = [
            'integer test case lesser' => [
                'conditionValue' => 999,
                'passValue' => 998,
                'unPassValue' => 1000,
                'operator' => 'lt',
                'typeCast' => 'integer'
            ],
            'integer test case greater' => [
                'conditionValue' => 999,
                'passValue' => 1000,
                'unPassValue' => 999,
                'operator' => 'gt',
                'typeCast' => 'integer'
            ],
            'integer test case lte' => [
                'conditionValue' => 999,
                'passValue' => 999,
                'unPassValue' => 1000,
                'operator' => 'lte',
                'typeCast' => 'integer'
            ],
            'integer test case gte' => [
                'conditionValue' => 999,
                'passValue' => 999,
                'unPassValue' => 998,
                'operator' => 'gte',
                'typeCast' => 'integer'
            ],
            'array test case' => [
                'conditionValue' => 'a;b;c',
                'passValue' => 'a',
                'unPassValue' => 'd',
                'operator' => 'in',
                'typeCast' => 'array'
            ]
        ];

        foreach ($testCases as $case => $params) {
            $this->specify("check condition $case", function () use ($params) {
                $this->specify('i want check case pass condition', function () use ($params) {
                    $condition = new PromotionCondition([
                        'name' => 'testerProperty',
                        'value' => $params['conditionValue']
                    ]);
                    $config = new PromotionConditionConfig([
                        'name' => 'testerProperty',
                        'operator' => $params['operator'],
                        'type_cast' => $params['typeCast']
                    ]);
                    $condition->populateRelation('promotionConditionConfig', $config);
                    $request = $this->make(PromotionRequestParam::className(), [
                        $condition->name => $params['passValue']
                    ]);
                    verify($condition->checkConditionRecursive($request))->true();
                });

                $this->specify('i want check case not pass condition', function () use ($params) {
                    $condition = new PromotionCondition([
                        'name' => 'testerProperty',
                        'value' => $params['conditionValue']
                    ]);
                    $config = new PromotionConditionConfig([
                        'name' => 'testerProperty',
                        'operator' => $params['operator'],
                        'type_cast' => $params['typeCast']
                    ]);
                    $condition->populateRelation('promotionConditionConfig', $config);
                    $request = $this->make(PromotionRequestParam::className(), [
                        $condition->name => $params['unPassValue']
                    ]);
                    verify($condition->checkConditionRecursive($request))->false();
                });
            });
        }
    }
}