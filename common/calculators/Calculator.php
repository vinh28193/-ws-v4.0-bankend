<?php

namespace common\calculators;

use Yii;
use yii\base\BaseObject;

/**
 * Class Calculator
 * @package common\components\calculator
 */
class Calculator extends BaseObject
{
    const TYPE_FIXED = 'F';
    const TYPE_PERCENT = 'P';

    public $type = self::TYPE_FIXED;
    public $value = 0;
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

    protected function checkCondition($target)
    {
        $pass = false;
        foreach ($this->getConditions() as $condition) {
            $pass = $condition->pass($target);
            if (!$pass) {
                break;
            }
        }
        return $pass;
    }

    public function register($rule)
    {
        $this->value = $rule['value'];
        $this->unit = $rule['unit'];
        $this->type = $rule['type'];
        $LCondition = [];
        foreach ($rule['conditions'] as $condition) {
            $condition['class'] = Condition::className();
            $LCondition[] = Yii::createObject($condition);
        }
        $this->setConditions($LCondition);
    }

    public function calculator($target)
    {
        if (!$this->checkCondition($target)) {
            return 0;
        }
        try {
            $data = (new ResolveTarget())->resolve($target, $this->unit);
            return $this->calculatorInternal($data);
        } catch (\Exception $exception) {
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
                return $data * $this->value;
            case self::TYPE_PERCENT:
                return $data * ($this->value / 100);
            default:
                return 0;
        }
    }


    public function deception()
    {
        $string = [];
        $array = [];
        foreach ($this->getConditions() as $condition) {
            $string[] = $condition->deception()['string'];
            $array[] = $condition->deception()['array'];
        }
        $string = implode(', ', $string);
        $type = $this->type === self::TYPE_PERCENT ? '%' : '';
        return [
            'array' => [
                'value' => $this->value,
                'type' => $this->type,
                'unit' => $this->unit,
                'conditions' => $array
            ],
            'string' => "$this->value{$type} {$this->unit} if $string",
        ];

    }
}