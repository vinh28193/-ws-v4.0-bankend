<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 10:40
 */

namespace common\components\conditions;


use common\calculators\Calculator;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;

trait ConditionTrait
{

    /**
     * @return array
     */
    public function getCondition()
    {
        $data = $this->condition_data;
        if ($data === null) {
            return [];
        }
        return json_decode($data, true);
    }

    public function executeCondition(AdditionalFeeInterface $additional)
    {

        $condition = $condition = $this->getCondition();
        if (!empty($condition)) {
            return false;
        }
        $value = CalculatorService::calculator($condition, $additional);
        $value *= $additional->getShippingQuantity();
        return [$value, $value * $additional->getExchangeRate()];

    }
}