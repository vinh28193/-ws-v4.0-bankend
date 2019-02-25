<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:13
 */

namespace common\components\conditions;


class GSTCondition extends BaseCondition
{
    public $name = 'GSTCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        $gstRate = 0.0;
        $priceBeforeGST = $additionalFee->getTotalAdditionFees([
            'origin_fee',
            'origin_tax_fee',
            'origin_shipping_fee',
            'weshop_fee',
            'custom_fee'
        ])[0];
        switch ($additionalFee->getStoreManager()->getId()) {
            case 1: //vn no-gst
                return 0.0;
            case 2: //my
//                $gstRate = 0.06;
                return 0.0;

                break;
            case 3: //indo no-gst
                return 0.0;
            case 8: //sg no-gst
                return 0.0;
            case 4: //ph
                $gstRate = 0.12;
                return $gstRate * ($priceBeforeGST) - $additionalFee->getTotalOriginPrice();
            case 5: //th
                $gstRate = 0.07;
        }
        return $gstRate * $priceBeforeGST;
    }
}