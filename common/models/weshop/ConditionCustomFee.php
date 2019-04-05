<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 9:30 SA
 */

namespace common\models\weshop;


class ConditionCustomFee
{
    const KEY_PRICE = 'price';
    const KEY_QUANTITY = 'quantity';
    const KEY_CONDITION = 'condition';
    const KEY_CHAR = 'char';

    const OPERATOR_greater_equal = '>=';
    const OPERATOR_greater = '>';
    const OPERATOR_equal = '==';
    const OPERATOR_less_equal = '<=';
    const OPERATOR_less = '<';
    const OPERATOR_different = '!=';

    const condition_NEW = 'new';
    const condition_used = 'used';


    public $value;
    public $key;
    public $type = 'int';
    public $operator;

    /**
     * @param TargetCustomFee $target
     * @return bool
     */
    public function  checkCondition($target){
        $k = $this->key;
        $val = $target->$k;
//        echo $valueL . " " .$operation ." " .$valueR.PHP_EOL;
        switch ($this->operator){
            case self::OPERATOR_greater:
                return $val > $this->value;
                break;
            case self::OPERATOR_less:
                return $val < $this->value;
                break;
            case self::OPERATOR_greater_equal:
                return $val >= $this->value;
                break;
            case self::OPERATOR_less_equal:
                return $val <= $this->value;
                break;
            case self::OPERATOR_equal:
                return $val == $this->value;
            break;
            case self::OPERATOR_different:
                return $val != $this->value;
            break;
            default:
                return false;
                break;
//            case 'like':
//                return strpos('--'.strtolower($valueL),strtolower($this->value)) > 0 ? true : false;
        }
    }
}
