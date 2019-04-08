<?php
/**
 *
 */

namespace common\tests\calculators;

use stdClass;
use common\tests\UnitTestCase;
use common\calculators\Condition;

class ConditionTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testPass()
    {
        $condition = new Condition();
        $condition->key = 'testKey';
        $condition->operator = Condition::OPERATOR_LESS;
        $condition->value = 50;

        $target = new stdClass();
        $target->testKey = 49;
        verify($condition->pass($target))->true();

        $target->testKey = 50;
        verify($condition->pass($target))->false();

        $condition->operator = Condition::OPERATOR_LESS_EQUAL;
        $target->testKey = 50;
        verify($condition->pass($target))->true();

        $condition->operator = Condition::OPERATOR_GREATER;
        $target->testKey = 50;
        verify($condition->pass($target))->false();
        $target->testKey = 49;
        verify($condition->pass($target))->false();

        $condition->operator = Condition::OPERATOR_GREATER_EQUAL;
        $target->testKey = 50;
        verify($condition->pass($target))->true();
        $target->testKey = 49;
        verify($condition->pass($target))->false();
        $target->testKey = 51;
        verify($condition->pass($target))->true();

        $condition->operator = Condition::OPERATOR_EQUAL;
        $target->testKey = 50;
        verify($condition->pass($target))->true();
        $target->testKey = 49;
        verify($condition->pass($target))->false();
        $target->testKey = 51;
        verify($condition->pass($target))->false();

        $condition->operator = Condition::OPERATOR_DIFFERENT;
        $target->testKey = 50;
        verify($condition->pass($target))->false();
        $target->testKey = 49;
        verify($condition->pass($target))->true();
        $target->testKey = 51;
        verify($condition->pass($target))->true();
    }

    public function testToArray(){
        $condition = new Condition();
        $condition->key = 'testKey';
        $condition->operator = Condition::OPERATOR_LESS;
        $condition->value = 50;
        $condition->type = Condition::TYPE_STRING;
        verify($condition->toArray())->equals([
            'key' => 'testKey',
            'operator' => '<',
            'value' => 50,
            'type' => 'string',
        ]);
    }

    public function testDeception(){
        $condition = new Condition();
        $condition->key = 'testKey';
        $condition->operator = Condition::OPERATOR_LESS;
        $condition->value = 50;
        $condition->type = Condition::TYPE_STRING;
        verify($condition->deception())->equals("testKey less (string)50");
    }
}