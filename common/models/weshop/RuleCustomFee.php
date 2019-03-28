<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 9:27 SA
 */

namespace common\models\weshop;


class RuleCustomFee
{
    const TYPE_FEE_USD = '$';
    const TYPE_FEE_PERCENT = '%';

    const UNIT_QUANTITY = 'quantity';
    const UNIT_WEIGHT = 'weight';

    /** @var ConditionCustomFee[] $conditions */
    public $conditions;
    public $fee;
    public $unit;
    public $type_fee;

    /**
     * @param array $rule
     */
    public function set($rule){
        ///#Todo hàm set dữ liệu cho object
        $this->fee = $rule['fee'];
        $this->unit = $rule['unit'];
        $this->type_fee = $rule['type_fee'];
        $LCondition = [];
        foreach ($rule['conditions'] as $condition){
            $nCondition = new ConditionCustomFee();
            $nCondition->value = $condition['value'];
            $nCondition->operator = $condition['operator'];
            $nCondition->type = $condition['type'];
            $nCondition->key = $condition['key'];
            $LCondition[] = $nCondition;
        }
        $this->conditions = $LCondition;
    }

    /**
     * @param TargetCustomFee $target
     * @return bool
     */
    public function checkRule($target){
        $check = false;
        foreach ($this->conditions as $condition){
            $check = $condition->checkCondition($target);
            if (!$check){
                break;
            }
        }
        return $check;
    }


    /**
     * @param TargetCustomFee $target
     * @return float|int
     */
    public function getTotalCustomFee($target){
        $amount = 0;
        if($this->checkRule($target)){
            $u = $this->unit;
            switch ($this->type_fee){
                case self::TYPE_FEE_USD:
                    $amount = $target->$u * $this->fee;
                    break;
                case self::TYPE_FEE_PERCENT:
                    $amount = $target->price * $target->$u * ($this->fee/100);
                    break;
            }
        }
        return $amount;
    }
}
