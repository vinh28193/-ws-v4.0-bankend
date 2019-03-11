<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:49
 */

namespace common\models\boxme;


class ShipmentInfoForm
{
    public $content = "";
    public $cod_amount = "";
    public $total_parcel = "";
    public $total_amount = "";
    public $chargeable_weight = "";
    public $description = "Cho khách hàng xem khi giao hàng"; // | Show to customers when delivery
    /** @var ParcelInfoForm[] $parcels */
    public $parcels;
}