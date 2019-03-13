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

$faker = \Faker\Factory::create('vi_VN');
return [
    'id' => $index + 1,
    'country_id' => 1,
    'name' => $faker->city,
    'name_local' => $faker->city,
    'name_alias' => $faker->city,
    'display_order' => 0,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'remove' => 0,
];