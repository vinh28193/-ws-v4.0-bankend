<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:55
 */

namespace common\products;


class Provider extends \yii\base\BaseObject
{
    public $prov_id;
    public $name;
    public $image;
    public $website;
    public $location;
    public $country_code;
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
    public $price_api;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //bỏ dấu + thành dấu *. vì để  + khi get từ url sẽ nhận nhầm thành dấu cách
        $this->prov_id = str_replace('+','*',base64_encode($this->name .'-'. $this->condition .'-'. $this->price));
    }
}