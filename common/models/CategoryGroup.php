<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use common\models\weshop\ConditionCustomFee;
use common\models\weshop\RuleCustomFee;
use common\models\weshop\TargetCustomFee;

class CategoryGroup extends \common\models\db\CategoryGroup
{
    public function customFeeCalculator($price, $quantity, $weight, $isNew = false){
        $rules = json_decode($this->rule,true);
        $total_custom_fee = 0;
        $target = new TargetCustomFee();
        $target->price = $price;
        $target->quantity = $quantity;
        $target->weight = $weight;
        $target->condition = $isNew ? ConditionCustomFee::condition_NEW : ConditionCustomFee::condition_used;
        foreach ($rules as $rule){
            $crRule = new RuleCustomFee();
            $crRule->set($rule);
            $total_custom_fee = $crRule->getTotalCustomFee($target);
            if($total_custom_fee > 0){
                break;
            }
        }
        return $total_custom_fee;
    }
}
