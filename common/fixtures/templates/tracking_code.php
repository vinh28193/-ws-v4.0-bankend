<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 20:42
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
return [
    'id' => $id,
    'store_id' => 1,
    'package_id' => null,
    'package_item_id' => null,
    'weshop_tag' => \common\helpers\WeshopHelper::generateTag($index,'WS',16),
    'order_id' => null,
    'seller_id' => null,
    'seller_tracking' => \common\helpers\WeshopHelper::generateTag($index,'',16),
    'seller_tracking_reference_1' => \common\helpers\WeshopHelper::generateTag($index,'REF1_',16),
    'seller_tracking_reference_2' => \common\helpers\WeshopHelper::generateTag($index,'REF2_',16),
    'seller_weight' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'seller_quantity' => $faker->numberBetween(1,99),
    'seller_dimension_width' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'seller_dimension_length' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'seller_dimension_height' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'seller_shipped_at' => $faker->unixTime,
    'receiver_warehouse_id' => $faker->numberBetween(1,10),
    'receiver_warehouse_note' => $faker->realText(100),
    'receiver_warehouse_send_at' =>  $faker->unixTime,
    'local_warehouse_id' => $faker->numberBetween(1,10),
    'local_warehouse_tag' => \common\helpers\WeshopHelper::generateTag($index,'CBTAG_',16),
    'local_warehouse_weight' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'local_warehouse_quantity' => $faker->numberBetween(1,99),
    'local_warehouse_dimension_width' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'local_warehouse_dimension_length' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'local_warehouse_dimension_height' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'local_warehouse_note' => $faker->realText(100),
    'local_warehouse_status' => $faker->randomElement(['open','close', null]),
    'local_warehouse_send_at' => $faker->unixTime,
    'operation_note' =>  $faker->realText(100),
    'status' => 'NEW',
    'remove' => 0,
    'created_by' => 1,
    'created_at' =>  $faker->unixTime,
    'updated_by' => 1,
    'updated_at' =>  $faker->unixTime,
];