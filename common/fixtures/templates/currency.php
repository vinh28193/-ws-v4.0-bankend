<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 09:25
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;

return [
    'id' => $id,
    'version'=>'4.0',
    'name' => $faker->currencyCode,
    'currency_code' => $faker->currencyCode,
    'currency_symbol' => $faker->currencyCode,
    'status' => $faker->numberBetween(0,1),
];
