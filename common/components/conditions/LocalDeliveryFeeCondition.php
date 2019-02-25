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
        $shippingWeight = $additionalFee->getShippingWeight();
        switch ($additionalFee->getStoreManager()->getId()) {
            case 1://vn
//                return 0.0;
                if ($additionalFee->getItemType() != 'AMAZON_JP') {
                    return 0.0;
                } else {
                    return $shippingWeight <= 1 ? 2 : 0.0;
                }
            case 2: //my
                return $shippingWeight * 4.0;
            case 3: //ph
                return $shippingWeight * 3.5;
            case 4: //th
                if ($shippingWeight < 1.0) {
                    return 3.0;
                } else {
                    return 3.0 + ($shippingWeight - 1) * 0.7;
                }
        }
        return 0.0;
    }
}