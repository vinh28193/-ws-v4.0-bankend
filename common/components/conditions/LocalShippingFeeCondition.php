<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:14
 */

namespace common\components\conditions;


class LocalShippingFeeCondition extends BaseCondition
{
    public $name = 'LocalShippingFeeCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value;
    }
}