<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 09:50
 */

namespace common\components\conditions;


class SimpleCondition extends BaseCondition
{

    /**
     * @var string
     */
    public $name = 'SimpleCondition';

    /**
     * @param int $value
     * @param \ws\base\AdditionalFeeInterface $additionalFee
     * @param $storeAdditionalFee
     * @return int
     */
    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value;
    }
}