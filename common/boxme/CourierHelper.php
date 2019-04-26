<?php


namespace common\boxme;


use common\models\PackageItem;
use Yii;
use yii\helpers\ArrayHelper;

class CourierHelper
{

    /**
     * @return mixed
     */
    public static function getBlackLists()
    {
        return ArrayHelper::getValue(Yii::$app->params,'black_list_courier',[]);
    }

    /**
     * @param $couriers
     * @param $rules
     * @param $shipment \common\models\Shipment
     * @return array;
     */
    public static function parserRule($couriers, $rules, $shipment)
    {
        if (count($rules) > 1) {
            foreach ($couriers as $courier) {
                if (($code = $courier['service_code']) === null || in_array($code, self::getBlackLists()) || !in_array($code, $rules)) {
                    continue;
                }
                if (self::getValidCourier($courier, $shipment)) {
                    return $courier;
                }
            }
            return array_shift($couriers);
        }

        foreach ($couriers as $courier) {
            if (($code = $courier['service_code']) === null || in_array($code, self::getBlackLists())) {
                continue;
            }
            if (self::getValidCourier($courier, $shipment)) {
                return $courier;
            }
            return array_shift($couriers);
        }
    }

    /**
     * @param $courier array
     * @param $shipment \common\models\Shipment
     * @return boolean
     */
    private static  function getValidCourier($courier, $shipment)
    {
        $max = 0;
        if (count($shipment->packageItems) > 0) {
            foreach ($shipment->packageItems as $packageItem) {
                /** @var PackageItem $item */
                $temp = $packageItem->dimension_w > $packageItem->dimension_l ? $packageItem->dimension_w : $packageItem->dimension_l;
                $temp = $packageItem->dimension_h > $temp ? $packageItem->dimension_h : $temp;
                $max = $temp > $max ? $temp : $max;
            }
        }
        $serviceCode = strtoupper('-' . $courier['service_code']);
        if (strpos($serviceCode, 'DHLE') > 0) {
            return $shipment->total_weight < 30000;
        } elseif (strpos($serviceCode, 'GHTK') > 0) {
            return $shipment->total_weight < 5000 && $shipment->total_cod < 3000000 && $max < 50;
        } elseif (strpos($serviceCode, 'S60') > 0) {
            return $shipment->total_weight < 5000 && $shipment->is_insurance === false && $max < 50;
        }
        return true;
    }
}