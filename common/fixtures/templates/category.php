<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 15:37
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;

return [
    'id' => $id,
    'alias' => $faker->unique()->randomNumber(),
    'site' => $faker->randomElement(['ebay','amazon','amazon-jp']),
    'origin_name' => $faker->text(15),
    'category_group_id' => null,
    'parent_id' => null,
    'description' => null,
    'weight' => null,
    'inter_shipping_b' => null,
    'custom_fee' => null,
    'level' => 1,
    'path' => null,
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'active' => 1,
    'remove' => 0,
];