<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:48
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;

return [
    'id' => $id,
    'name' => $faker->name,
    'link_store' => $faker->url,
    'rate' => $faker->randomFloat(1,0,5),
    'description' => $faker->realText(50),
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => $faker->numberBetween(0,1),
    'portal' => $faker->randomElement(['EBAY',"AMAZON","AMAZON-JP"]),
];