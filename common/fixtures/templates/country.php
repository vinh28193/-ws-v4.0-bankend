<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 08:46
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$code = $faker->unique()->countryCode;
return [
    'id' => $id,
    'name' => $faker->country,
    'country_code' => $code,
    'country_code_2' => $code,
    'language' => $faker->languageCode,
    'status' => $faker->numberBetween(0,1),
];