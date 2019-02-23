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
        return $value;
    }
}