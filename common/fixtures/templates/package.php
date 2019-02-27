<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 14:03
 */

use  common\fixtures\components\FixtureUtility;
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$countOrders = rand(1,4);
$orders = "";
for($ind = 1; $ind<=$countOrders;$ind++){
    $id_order = rand(1,1000);
    $products = (FixtureUtility::getDataWithColumn('.\common\fixtures\data\product.php',null,['order_id' => $id_order]));
    if(count($products)){
        $orders .=  $ind < $countOrders ? rand(1,1000)."," : rand(1,1000);
    }else{
        $ind -=1;
    }
}

return [
    'id' => $id,
    'package_code' => FixtureUtility::getRandomCode(11,1),
    'tracking_seller' => FixtureUtility::getRandomCode(),
    'order_ids' => $orders,
    'tracking_reference_1' => FixtureUtility::getRandomCode(),
    'tracking_reference_2' => FixtureUtility::getRandomCode(),
    'manifest_code' => FixtureUtility::getRandomCode(5),
    'package_weight' => $faker->randomFloat(2,5,100)*1000,
    'package_dimension_l' => $dl = $faker->randomFloat(2,5,100),
    'package_dimension_w' => $dw = $faker->randomFloat(2,5,100),
    'package_dimension_h' => $dh = $faker->randomFloat(2,5,100),
    'package_change_weight' => round(($dl * $dw * $dh)/5,2),
    'seller_shipped' => $seller_shipped = $faker->unixTime(),
    'stock_in_us' => $stock_in_us = $faker->randomElement([null,rand($seller_shipped,time())]),
    'stock_out_us' => $stock_out_us = $stock_in_us ? $faker->randomElement([null,rand($stock_in_us,time())]) : null,
    'stock_in_local' => $stock_in_local = $stock_out_us ? $faker->randomElement([null,rand($stock_out_us,time())]) : null,
    'lost' => $lost = $faker->randomElement([null,null,null,null,null,null,null,null,null,rand($seller_shipped-60*60*24*3,$seller_shipped)]),
    'current_status' => $lost ? "LOST" : $stock_in_local ? "STOCK_IN_LOCAL" : $stock_out_us ? "STOCK_OUT_US" : $stock_in_us ? "STOCK_IN_US" : "SELLER_SHIPPED",
    'warehouse_id' => rand(1,10),
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime(),
    'remove' => 0,
];