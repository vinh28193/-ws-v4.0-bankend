<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:14
 */

namespace common\components\conditions;


class StoreFeeCondition extends BaseCondition
{
    public $name = 'StoreFeeCondition';


    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        $weshopFeeRate = 0.0;

        switch ($additionalFee->getStoreManager()->getId()) {
            case 1: //vn
                $weshopFeeRate = $additionalFee->getIsForWholeSale() ? 0.05 : 0.08;
                break;
        }
        return $additionalFee->getTotalOriginPrice() * $weshopFeeRate;
    }
}