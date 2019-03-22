<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-22
 * Time: 09:21
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */


$templates = ['RVS', 'VA', 'HNC'];

$id = $index + 1;

$code = $faker->randomElement($templates);
$code .= rand(1000, 9999);

return [
    'id' => $id,
    'manifest_code' => $code,
    'send_warehouse_id' => $faker->numberBetween(1,10),
    'receive_warehouse_id' => $faker->numberBetween(1,10),
    'us_stock_out_time' => $faker->unixTime,
    'local_stock_in_time' => $faker->unixTime,
    'local_stock_out_time' => $faker->unixTime,
    'store_id' => 1,
    'created_by' => 1,
    'updated_by' => 1,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'active' => 1,
];