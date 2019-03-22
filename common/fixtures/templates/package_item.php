<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-16
 * Time: 08:54
 */

use common\fixtures\components\FixtureUtility;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'package_id' => $faker->numberBetween(1,999),
    'version'=>'4.0',
    'package_code' => FixtureUtility::getRandomCode(11,1),
    'box_me_warehouse_tag' => $faker->randomElement(['CB_TAG-'.FixtureUtility::getRandomCode(16),null]),
    'order_id' => $faker->numberBetween(1,999),
    'sku' => $faker->md5,
    'quantity' => $faker->numberBetween(1,999),
    'weight' => $faker->numberBetween(1,999),
    'dimension_l' => $dl = $faker->numberBetween(1,999),
    'dimension_w' => $dw = $faker->numberBetween(1,999),
    'dimension_h' => $dh = $faker->numberBetween(1,999),
    'change_weight' => round(($dl * $dw * $dh)/5,2),
    'stock_in_local' => $faker->randomElement([$faker->unixTime,null]),
    'stock_out_local' => $faker->randomElement([$faker->unixTime,null]),
    'at_customer' =>$faker->randomElement([$faker->unixTime,null]),
    'returned' => $faker->randomElement([$faker->unixTime,null]),
    'lost' => $faker->randomElement([$faker->unixTime,null]),
    'current_status' => $faker->randomElement([ "LOST", "STOCK_IN_LOCAL" ,"STOCK_OUT_LOCAL" ,"AT_CUSTOMER" , "RETURNED"]),
    'shipment_id' => null,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'remove' => 0,
    ];
