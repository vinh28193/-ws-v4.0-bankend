<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 16:21
 */

namespace common\tests\stubs;

use common\components\conditions\BaseCondition;

class TestCondition extends BaseCondition
{
    public $name = 'Test';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value;
    }
}