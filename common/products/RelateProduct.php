<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:20
 */

namespace common\products;


use yii\helpers\ArrayHelper;

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

    public static function setRelateProducts($arrayRelateEbay){
        $re = [];
        foreach ((array)$arrayRelateEbay as $item){
            $temp = new self();
            $temp->item_id = ArrayHelper::getValue($item,'itemId');
            $temp->title = ArrayHelper::getValue($item,'title');
            $temp->category_id = ArrayHelper::getValue($item,'primaryCategoryId');
            $temp->category_name = ArrayHelper::getValue($item,'primaryCategoryName');
            $temp->image = ArrayHelper::getValue($item,'imageURL');
            $temp->retail_price = ArrayHelper::getValue($item,'buyItNowPrice') ? ArrayHelper::getValue(ArrayHelper::getValue($item,'buyItNowPrice'),'value') : 0;
            $temp->sell_price = $temp->retail_price;
            $re[] = $temp;
        }
        return $re;
    }
}