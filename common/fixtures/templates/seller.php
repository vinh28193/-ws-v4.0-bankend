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
    'seller_name' => $faker->unique()->name,
    'seller_store_rate' => $faker->unique()->url,
    'seller_store_rate' => $faker->unique()->randomFloat(1,0,10),
    'seller_store_description' => $faker->realText(50),
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'seller_remove' => $faker->numberBetween(0,1),
    'portal' => $faker->randomElement(['EBAY',"AMAZON","AMAZON-JP"]),
];