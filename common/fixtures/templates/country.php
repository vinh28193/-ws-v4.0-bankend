<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'id' => $index + 1,
    'version'=>'4.0',
    'name' => $faker->country,
    'country_code' => $faker->unique()->countryCode,
    'country_code_2' => $faker->unique()->countryCode,
    'language' => $faker->unique()->languageCode,
    'status' => $faker->numberBetween(0,1),
];
