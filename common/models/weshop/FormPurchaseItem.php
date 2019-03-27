<?php

namespace common\models\weshop;

use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: galat
 * Date: 25/03/2019
 * Time: 1:37 CH
 */

class FormPurchaseItem extends Model
{
    public $id;
    public $order_id;
    public $ItemType;
    public $Name;
    public $image;
    public $price;
    public $us_ship;
    public $us_tax;
    public $price_purchase;
    public $us_ship_purchase;
    public $us_tax_purchase;
    public $sku;
    public $ParentSku;
    public $sellerId;
    public $condition;
    public $variation;
    public $quantity;
    public $quantityPurchase;
    public $paidToSeller;
    public $paidTotal;
    public $typeCustomer;
}
