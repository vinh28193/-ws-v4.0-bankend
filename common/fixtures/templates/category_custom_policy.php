<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 15:42
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;

return [
    'id' => $id,
    'name' => $faker->unique()->text(15),
    'description' => null,
    'code' => null,
    'limit' => $faker->numberBetween(1,100),
    'is_special' => 0,
    'min_price' => null,
    'max_price' => null,
    'custom_rate_fee' => $faker->unique()->numberBetween(1,10),
    'use_percentage' => 1,
    'custom_fix_fee_per_unit' => 1,
    'custom_fix_fee_per_weight' => 0,
    'custom_fix_percent_per_weight' => 0,
    'store_id' => 1,
    'item_maximum_per_category' => null,
    'weight_maximum_per_category' => 0,
    'sort_order' => 0,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'active' => $faker->numberBetween(0,1),
    'remove' => $faker->numberBetween(0,1),
];