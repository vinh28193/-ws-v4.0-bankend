<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:23
 */
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$name = $faker->unique()->city;
return [
    'id' => $id,
    'country_id' => 1,
    'name' => $name,
    'name_local' => $name,
    'name_alias' => $name,
    'display_order' => 0,
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => 0,
];