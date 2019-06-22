<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use Yii;
use yii\helpers\Json;
use common\models\db\CategoryGroup as DbCategoryGroup;
use common\calculators\CalculatorService;
use common\additional\AdditionalFeeInterface;

class CategoryGroup extends DbCategoryGroup
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const IS_SPECIAL = 1;
    const NON_SPECIAL = 0;

    /**
     * @param  $target
     * @return float|int
     */
    public function customFeeCalculator(AdditionalFeeInterface $target)
    {
        $start = microtime(true);

        if ($this->rule === null || $this->rule === '') {
            return 0.0;
        }
        $rules = Json::decode($this->rule, true);
        $value =  CalculatorService::calculator($rules, $target);
        $time = microtime(true) - $start;
        Yii::info("Custom fee $value: calculate ended (time: " . sprintf('%.3f', $time) . " s)", 'CUSTOM FEE INFORMATION');
        return $value;
    }

    public function getIsSpecial(AdditionalFeeInterface $target)
    {
        if (($check = $this->is_special === self::IS_SPECIAL) === true) {
            return $check;
        } elseif (!$check && $this->special_min_amount > 0) {
            return $target->getTotalOrigin() >= $this->special_min_amount;
        }
        return false;
    }
}
