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

return [
    'id' => $index + 1,
    'name' => $faker->unique()->name,
    'link_store' => $faker->unique()->url,
    'rate' => $faker->unique()->randomFloat(1,0,10),
    'description' => $faker->realText(50),
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => $faker->numberBetween(0,1),
    'portal' => $faker->randomElement(['EBAY',"AMAZON","AMAZON-JP"]),
];