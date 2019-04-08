<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use common\calculators\CalculatorService;
use common\models\weshop\ConditionCustomFee;
use common\models\weshop\RuleCustomFee;
use common\models\weshop\TargetCustomFee;

class CategoryGroup extends \common\models\db\CategoryGroup
{
    /**
     * @param TargetCustomFee $target
     * @return float|int
     */
    public function customFeeCalculator($target)
    {
        $rules = json_decode($this->rule, true);
        return CalculatorService::calculator($rules, $target);
    }
}
