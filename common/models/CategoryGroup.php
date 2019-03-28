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
    /**
     * @param TargetCustomFee $target
     * @return float|int
     */
    public function customFeeCalculator($target){
        $rules = json_decode($this->rule,true);
        $total_custom_fee = 0;
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
