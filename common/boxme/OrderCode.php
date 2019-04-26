<?php


namespace common\boxme;


use common\models\Package;
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
        810 => 'Destroy parcel	(Destroy parcel and dont need return to pickup address (Final status))',
    ];

    public static $mappingPackageStatus = [
        Package::STATUS_AT_THE_VN_WAREHOUSE => [
            700, 701, 702, 703,
        ],
        Package::STATUS_REQUEST_SHIP_OUT => [
            200, 201, 202, 203, 210, 211,
        ],
        Package::STATUS_DELIVERING_TO_CUSTOMER => [
            300, 310, 400, 410, 420, 430, 500, 510, 511, 520, 530,
        ],
        Package::STATUS_DELIVERED => [800],
        Package::STATUS_RETURNED => [600]
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
     * @param $code integer
     * @return bool|string
     */
    public static function getPackageStatus($code)
    {
        foreach (self::$mappingPackageStatus as $status => $listCodes) {
            if (ArrayHelper::isIn($code, $listCodes)) {
                return $status;
            }
        }
        return false;
    }
}