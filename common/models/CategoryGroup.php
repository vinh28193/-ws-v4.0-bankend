<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use yii\helpers\Json;
use common\models\db\CategoryGroup as DbCategoryGroup;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;

class CategoryGroup extends DbCategoryGroup
{
    /**
     * @param  $target
     * @return float|int
     */
    public function customFeeCalculator(AdditionalFeeInterface $target)
    {
        if($this->rule === null){
            return 0.0;
        }
        $rules = Json::decode($this->rule, true);
        return CalculatorService::calculator($rules, $target);
    }
}
