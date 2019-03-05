<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 2:04 PM
 */

namespace common\models\weshop\subproduct;

/**
 * @property Provider $provider;
 * */
class CateProduct
{
    public function __construct($data)
    {
        $productAttr = get_object_vars($this);
        foreach ($data as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_object($k1))
                    if ($k == $k1) {
                        $this->$k1 = $v;
                    }
            }
        }
    }

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