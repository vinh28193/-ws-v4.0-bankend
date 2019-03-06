<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 08:14
 */

namespace common\components\conditions;

use Yii;

class LocalShippingFeeCondition extends BaseCondition
{
    public $name = 'LocalShippingFeeCondition';

    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        $user = Yii::$app->getUser();
        if($user->isGuest){
            return $value;
        }
        $shipment = [
            'total_parcel' => $additionalFee->getShippingQuantity(),
            'chargeable_weight' => $additionalFee->getShippingWeight(),
            'total_amount' => $additionalFee->getTotalAdditionFees()[1],
            'parcels' => [
                'weight' => $additionalFee->getShippingWeight()
            ]
        ];
        return $value;

    }
}