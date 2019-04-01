<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:13
 */

namespace common\components\conditions;


class CustomFeeCondition extends BaseCondition
{

    public $name = 'CustomFeeCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        if (($category = $additionalFee->getCustomCategory()) !== null) {
            return $category->getCustomFee($additionalFee);
        }
        return 0.0;
    }
}