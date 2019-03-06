<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:20
 */

namespace common\products;


class RelateProduct extends \yii\base\BaseObject
{
    public $item_id;
    public $image;
    public $is_prime;
    public $rate_count;
    public $rate_star;
    public $retail_price;
    public $sell_price;
    public $title;
    public $category_id;
    public $category_name;
    public $provider;
    public $condition;
    public $start_time;
    public $end_time;
}