<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:50 PM
 */

namespace common\models\weshop\subproduct;


class Provider
{
    public function __construct($data)
    {
        $productAttr = get_object_vars($this);
        print_r($data);
        die;
        foreach ($data as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_object($k1))
                    if ($k == $k1) {
                        $this->$k1 = $v;
                    }
            }
        }
        //bỏ dấu + thành dấu *. vì để  + khi get từ url sẽ nhận nhầm thành dấu cách
        $this->provId = str_replace('+','*',base64_encode($this->name .'-'. $this->condition .'-'. $this->price));
    }

    public $provId;
    public $name;
    public $image;
    public $website;
    public $location;
    public $rating_score;
    public $rating_star;
    public $positive_feedback_percent;
    public $condition;
    public $fulfillment;
    public $is_free_ship;
    public $is_prime;
    public $price;
    public $tax_fee;
    public $shipping_fee;
    public $priceApi;
}