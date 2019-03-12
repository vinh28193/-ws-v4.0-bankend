<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 15:32
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$order = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDataWithColumn('.\common\fixtures\data\order.php',null));
$category = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDataWithColumn('.\common\fixtures\data\category.php',null));
$category_custom = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDataWithColumn('.\common\fixtures\data\category_custom_policy.php',null));
return [
    'id' => $id,
    'order_id' => $order['id'],
    'seller_id' => $order['seller_id'],
    'portal' => $order['portal'],
    'sku' => $faker->randomNumber(),
    'parent_sku' => $faker->randomNumber(),
    'link_img' => $faker->imageUrl(),
    'link_origin' => $faker->url,
    'category_id' => $category['id'],
    'custom_category_id' => $category_custom['id'],
    'quantity' => $quantity = $faker->numberBetween(1, 3),
    'quantity_customer' => $amount = $faker->numberBetween(10, 10000),
    'price_amount_local' => $amount_local = strtolower($order['portal']) == 'amazon-jp' ? $amount * 213 : $amount * 23500,
    'total_price_amount_local' => $amount_local* $quantity,
    'quantity_purchase' => 0,
    'quantity_inspect' => 0,
    'variations' => '',
    'variation_id' => '',
    'note_by_customer' => $faker->realText(20),
    'total_weight_temporary' => 0.5*$quantity,
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => 0,
    'currency_id' => $amount_local = strtolower($order['portal']) == 'amazon-jp' ? '￥' : '$',
    'currency_symbol' => $amount_local = strtolower($order['portal']) == 'amazon-jp' ? '￥' : '$',
    'exchange_rate' => $amount_local = strtolower($order['portal']) == 'amazon-jp' ? 213 : 23500,
];