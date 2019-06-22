<?php

namespace common\calculators;

use Exception;
use Yii;

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
     * price/quantity/weight
     * - price: khi tính theo phần trăm, giá trị trả về pà phần trăm của giá
     * - quantity tính theo đơn vị quantity, nếu là % thì vẫn là phần trăm của price, giá trị trả về nhân với quantity
     * - weight tính theo đơn vị quantity, nếu là % thì vẫn là phần trăm của price, giá trị trả về nhân với weight
     *      ví dụ, 10%/kg, giá 100$ tổng weight = 3kg
     *      => 3*(10% của 100$)
     *      nếu 10$/1kg, tổng weight = 3kg
     *      =>  3*10
     * @var string
     */
    public $unit;

    /**
     * giá trị tính toán nhỏ nhất,
     * @note : chỉ dùng cho type [[Calculator::TYPE_PERCENT]]
     * @var null
     */
    public $minValue = null;

    /**
     * quantity/weight
     * @var null|string
     */
    public $minUnit = null;
    /**
     * khi không có giá trị nào pass qua condition hay khi apply rule bị lỗi,
     * trả về giá trị mặc định
     * @var int
     */
    public $defaultValue = 0;
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
        $pass = true;
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
        if (isset($rule['minValue'])) {
            $this->minValue = $rule['minValue'];
        }
        if (isset($rule['defaultValue'])) {
            $this->defaultValue = $rule['defaultValue'];
        }
        if (isset($rule['minUnit'])) {
            $this->minUnit = $rule['minUnit'];
        }
        $conditions = [];
        if ($rule['conditions'] !== null && !empty($rule['conditions'])) {
            foreach ($rule['conditions'] as $condition) {
                $conditions[] = $this->createCondition($condition);
            }
        }

        $this->conditions = $conditions;
    }

    /**
     *
     * @param $target
     * @return float|int
     */
    public function calculator($target)
    {
        if (!$this->checkCondition($target)) {
            return $this->defaultValue;
        }

        Yii::info($this->deception(), __METHOD__);
        try {
            $unit = $this->resolveKey($this->unit);
            /**
             *  fix bug, tính toán theo cái, theo kg
             */

//            $min = null;
//            if ($this->min !== null && is_array($this->min) && $this->type === self::TYPE_PERCENT) {
//                $min = new self($this->min);
//                $min->min = null;
//                $min = $min->calculator($target);
//            }

            if ($unit === 'getShippingWeight' || $unit === 'getShippingQuantity') {
                $value = $this->resolve($target, 'getTotalOrigin');

                $unitValue = $this->resolve($target, $unit);
                $value = $this->calculatorInternal($value);
                $value *= $unitValue;
                return $this->ensureMinValue($value, $target);
            }
            $value = $this->resolve($target, $unit);
            $value = $this->calculatorInternal($value);
            return $this->ensureMinValue($value, $target);
        } catch (Exception $exception) {
            Yii::info($exception, __METHOD__);
            return $this->defaultValue;
        }
    }

    public function ensureMinValue($value, $target)
    {
        if ($this->type !== self::TYPE_PERCENT || $this->minValue === null) {
            return $value;
        }
        $unitValue = 1;
        if ($this->minUnit !== null) {
            $unitValue = $this->resolveKey($this->minUnit);
            $unitValue = $this->resolve($target, $unitValue);
        }
        $unitValue *= $this->minValue;
        return $unitValue < $value ? $value : $unitValue;
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
                return $this->defaultValue;
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
        $type = $this->type === self::TYPE_FIXED ? '$' : '%';
        $unit = $this->unit === 'quantity' ? 'each item' : ($this->unit === 'weight' ? 'each kg' : 'price');
        $string = "$this->value{$type} $unit";

        if ($this->conditions !== null && !empty($this->conditions)) {
            $deception = [];
            foreach ($this->conditions as $condition) {
                $deception[] = "'{$condition->deception()}'";
            }
            $deception = implode(' and ', $deception);
            $string .= " if $deception";
        }
        if ($this->minValue !== null) {
            $string .= ", min {$this->minValue}$";
            if ($this->minUnit !== null) {
                $string .= $this->minUnit === 'quantity' ? ' each item' : ' each weight';
            }

        }
        if ($this->defaultValue !== 0) {
            $string .= ", default {$this->defaultValue}$";
        }
        return $string;
    }
}