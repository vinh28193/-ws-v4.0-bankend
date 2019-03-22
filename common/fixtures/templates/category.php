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
    'version'=>'4.0',
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
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'active' => 1,
    'remove' => 0,
];
