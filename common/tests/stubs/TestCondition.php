<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 16:21
 */

namespace common\tests\stubs;


class TestCondition extends \common\components\conditions\BaseCondition
{

    public $name = 'TestCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value;
    }
}