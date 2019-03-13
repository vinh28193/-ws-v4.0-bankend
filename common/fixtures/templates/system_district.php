<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:28
 */
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
$name = $faker->unique()->streetName;
$id = $index + 1;
return [
    'id' => $id,
    'name' =>  $name,
    'name_local' => $name,
    'name_alias' => $name,
    'display_order' => 0,
    'province_id' => $faker->numberBetween(1,10),
    'country_id' => 1,
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => 0,
];