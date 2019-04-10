<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 17:58
 */

namespace common\products\amazon;


use common\products\BaseProduct;

class AmazonProduct extends BaseProduct
{

    const STORE_US = 'amazon.com';
    const STORE_JP = 'amazon.co.jp';

    public $store = self::STORE_US;

    public $manufacturer_description;
    public $best_seller;
    public $sort_desc;
    public $sell_price_special;
    public $load_sub_url;
}