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
$faker = \Faker\Factory::create('vi_VN');
return [
    'id' => $id = $index + 1,
    'name' =>  $faker->streetName,
    'name_local' => $faker->streetName,
    'name_alias' => $faker->streetName,
    'display_order' => 0,
    'province_id' => $faker->unique()->numberBetween(1,100),
    'country_id' => 1,
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => 0,
];