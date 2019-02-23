<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 10:30
 */

namespace common\components\conditions;


class ExchangeRateCondition extends BaseCondition
{

    /**
     * @var string
     */
    public $name = 'ExchangeRate';

    /**
     * @param int $value
     * @param \ws\base\AdditionalFeeInterface $additionalFee
     * @param $storeAdditionalFee
     * @return float|int
     */
    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        return $value * $additionalFee->getExchangeRate();
    }
}