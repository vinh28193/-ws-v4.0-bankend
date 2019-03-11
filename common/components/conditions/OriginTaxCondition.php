<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:12
 */

namespace common\components\conditions;


class OriginTaxCondition extends BaseCondition
{
    public $name = 'OriginTaxCondition';


    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        $originPrice = $additionalFee->getAdditionalFees()->getTotalAdditionFees('origin_fee')[0];
        if ($value < 1) {
            return $originPrice * $value;
        } elseif ($value > 1 && $value < 2) {
            return $originPrice * ($value - 1);
        } else {
            return $originPrice * $value / 100;
        }
    }
}