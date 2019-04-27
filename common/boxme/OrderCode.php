<?php


namespace common\boxme;


use common\models\DeliveryNote;
use common\models\Shipment;
use yii\helpers\ArrayHelper;

class OrderCode
{
    public static $orderStatusLists = [
        200 => 'Verified (Order have been approved)',
        201 => 'Picking',
        202 => 'Pickup failed',
        203 => 'On the way to drop-off',
        210 => 'Processing at warehouse',
        211 => 'Ready to ship at warehouse',
        212 => 'Out of stock',
        220 => 'Awaiting to sourcing',
        230 => 'Cross border processing',
        231 => 'Received at origin facility',
        232 => 'Departed at origin facility',
        233 => 'Cross border transit',
        235 => 'Customs cleared',
        234 => 'Customs onhold',
        236 => 'Received at desitination facility',
        237 => 'Departed at desitination facility',
        300 => 'Shipped',
        310 => 'Out for delivery',
        400 => 'Out of delivery',
        410 => 'Awaiting to return',
        420 => 'Return processing',
        430 => 'Return approved',
        500 => 'Returning',
        510 => 'Out of return',
        511 => 'Return refused',
        520 => 'Awaiting receive at facility',
        530 => 'Return to orgin facility',
        610 => 'Returned at orgin facility',
        600 => 'Returned (Return to your warehouse or Boxme warehouse (Final status))',
        700 => 'Cancelled by sellers',
        701 => 'Cancelled by operator',
        702 => 'Cancelled by partner',
        703 => 'Cancelled by system',
        704 => 'Pending for audit (Operator need check and verify next status of order)',
        800 => 'Delivered (Delivery successfull (Final status).)',
        810 => 'Destroy parcel (Destroy parcel and dont need return to pickup address (Final status))',
    ];

    /**
     * @var array mapping package status
     */
    public static $mappingPackageStatus = [
        DeliveryNote::STATUS_AT_THE_VN_WAREHOUSE => [
            700, 701, 702, 703,
        ],
        DeliveryNote::STATUS_REQUEST_SHIP_OUT => [
            200, 201, 202, 203, 210, 211,
        ],
        DeliveryNote::STATUS_DELIVERING_TO_CUSTOMER => [
            300, 310, 400, 410, 420, 430, 500, 510, 511, 520, 530,
        ],
        DeliveryNote::STATUS_DELIVERED => [800],
        DeliveryNote::STATUS_RETURNED => [600]
    ];

    /**
     * mapping shipment status
     * @var array
     */
    public static $mappingShipmentStatus = [
        Shipment::STATUS_APPROVED => [200],
        Shipment::STATUS_PICKING => [300],
        Shipment::STATUS_DELIVERING => [304],
        Shipment::STATUS_DELIVERED => [800],
        Shipment::STATUS_RETURNING => [400, 410, 420, 430, 500, 510, 511, 520],
        Shipment::STATUS_RETURNED => [600],
        Shipment::STATUS_CANCELED => [700,701,702,703,705],
        Shipment::STATUS_DESTROY => [810]
    ];

    /**
     * @param $code integer
     * @return string
     */
    public static function getOrderCodeDeception($code)
    {
        return isset(self::$orderStatusLists[$code]) ? self::$orderStatusLists[$code] : $code;
    }

    /**
     * @param $code
     * @param $mappings
     * @return bool|int|string
     */
    private static function getStatusByCode($code, $mappings)
    {
        foreach ($mappings as $status => $lists) {
            if (ArrayHelper::isIn($code, $lists)) {
                return $status;
            }
        }
        return false;
    }

    /**
     * @param $code integer
     * @return bool|string
     */
    public static function getPackageStatus($code)
    {
        return self::getStatusByCode($code, self::$mappingPackageStatus);
    }

    /**
     * @param $code
     * @return bool|int|string
     */
    public static function getShipmentStatus($code){
        return self::getStatusByCode($code, self::$mappingShipmentStatus);
    }
}