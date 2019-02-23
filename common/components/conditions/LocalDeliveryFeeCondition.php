<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:15
 */

namespace common\components\conditions;


class LocalDeliveryFeeCondition extends BaseCondition
{
    public $name = 'LocalDeliveryFeeCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value;
    }
}