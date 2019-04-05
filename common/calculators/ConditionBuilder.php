<?php


namespace common\calculators;

use yii\base\BaseObject;
use yii\base\InvalidParamException;

/**
 * (A===B) => [['===','A','B']]
 * (A===B || C===D) => ['||',['===','A','B'],['===','C','D']]
 * (A===B && C===D) => ['&&',['===','A','B'],['===','C','D']]
 * ((A===B && C===D) || E===F)  => ['||',['&&',['===','A','B'],['===','C','D']],['===','C','D']]
 * Class ConditionBuilder
 * @package common\components\calculator
 */
class ConditionBuilder extends BaseObject
{

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param $condition
     * @return string
     */
    public function build($condition)
    {
        if(is_string($condition)){
            return $condition;
        }
        $builders = [
            '&&' => 'normalCondition',
            '||' => 'normalCondition',
        ];
        if (!isset($condition[0])) {
            throw new InvalidParamException('Invalid condition array');
        }
        $operator = strtoupper($condition[0]);
        if (isset($builders[$operator])) {
            $method = $builders[$operator];
        } else {
            $operator = $condition[0];
            $method = 'simpleCondition';
        }
        array_shift($condition);
        return $this->$method($operator, $condition);
    }


    public function normalCondition($operator, $operands)
    {
        $parts = [];
        foreach ($operands as $operand) {
            $parts[] = $this->build($operand);
        }
        return '(' . implode(") $operator (", $parts) . ')';
    }

    /**
     * @param $operator
     * @param $operands
     * @return string
     */
    public function simpleCondition($operator, $operands)
    {
        if (count($operands) !== 2) {
            throw new InvalidParamException("Operator '$operator' requires two operands.");
        }

        list($column, $value) = $operands;

        return "$column $operator $value";
    }
}