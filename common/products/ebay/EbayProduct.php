<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 13:55
 */

namespace common\products\ebay;


class EbayProduct extends \common\products\BaseProduct
{

    public $type = self::TYPE_EBAY;


    public $shipping_details;

    public function init()
    {
        parent::init();
    }
}