<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:45
 */

namespace common\models\boxme;


class ConfigForm
{
    public $sort_mode = "best_price";
    public $order_type = "consolidate"; // consolidate , fulfill
    public $insurance = "Y"; // N, Y
    public $return_mode = "2";
    public $auto_approve = "Y"; // N, Y
    public $unit_metric = "metric";
    public $document = "";
    public $delivery_service = "metric";
    public $currency = "VNĐ"; // VNĐ | IDR
}