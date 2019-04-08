<?php

namespace common\calculators;

use Yii;
use Exception;

/**
 * Class Calculator
 * @package common\components\calculator
 * @property Condition[] $conditions
 */
class Calculator extends Resolver
{
    /**
     * constant for type of check
     */
    const TYPE_FIXED = 'F';
    const TYPE_PERCENT = 'P';

    public $type = self::TYPE_FIXED;
    /**
     * @var integer|string
     */
    public $value;
    /**
     * @var string
     */
    public $unit;

    /**
     * @var Condition[]
     */
    private $_conditions;

    /**
     * @return Condition[]
     */
    public function getConditions()
    {
        return $this->_conditions;
    }

    /**
     * @param $conditions
     */
    public function setConditions($conditions)
    {
        $this->_conditions = $conditions;
    }

    /**
     * @param $config
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    private function createCondition($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = Condition::className();
        }
        return Yii::createObject($config);
    }

    /**
     * check conditions, true if pass all condition
     * @param $target
     * @return bool
     */
    public function checkCondition($target)
    {
        $pass = false;
        foreach ($this->conditions as $condition) {
            $pass = $condition->pass($target);
            if (!$pass) {
                break;
            }
        }
        return $pass;
    }

    /**
     * @param $rule
     */
    public function register($rule)
    {
        $this->value = $rule['value'];
        $this->unit = $rule['unit'];
        $this->type = $rule['type'];
        $conditions = [];
        foreach ($rule['conditions'] as $condition) {
            $conditions[] = $this->createCondition($condition);
        }
        $this->conditions = $conditions;
    }

    /**
     * @param $target
     * @return float|int
     */
    public function calculator($target)
    {
        if (!$this->checkCondition($target)) {
            return 0;
        }
        try {
            $unit = $this->resolveKey($this->unit);
            /**
             *  fix bug, tính toán theo cái, theo kg
             */

            if ($unit === 'getShippingWeight' || $unit === 'getShippingQuantity') {

                $value = $this->resolve($target, 'getTotalOriginPrice');

                $unitValue = $this->resolve($target, $unit);
                $value = $this->calculatorInternal($value);
                $value *= $unitValue;
                return $value;
            }
            $value = $this->resolve($target, $unit);
            return $this->calculatorInternal($value);
        } catch (Exception $exception) {
            Yii::info($exception, __METHOD__);
            return 0;
        }
    }

    /**
     * @param $data
     * @return float|int
     */
    private function calculatorInternal($data)
    {
        switch ($this->type) {
            case self::TYPE_FIXED:
                return $this->value;
            case self::TYPE_PERCENT:
                return $data * ($this->value / 100);
            default:
                return 0;
        }
    }

    public function toArray()
    {
        $array = [];
        foreach ($this->conditions as $condition) {
            $array[] = $condition->toArray();
        }
        return [
            'value' => $this->value,
            'type' => $this->type,
            'unit' => $this->unit,
            'conditions' => $array
        ];
    }

    /**
     * @return string get deception
     */
    public function deception()
    {
        $deception = [];
        foreach ($this->conditions as $condition) {
            $deception[] = $condition->deception();
        }
        $string = implode(', ', $deception);
        $type = $this->type === self::TYPE_PERCENT ? '%' : $this->type;
        $unit = $this->unit === 'quantity' ? 'each item' : ($this->unit === 'weight' ? 'each kg' : 'each price');
        return "$this->value{$type} $unit if $string";

    }
}