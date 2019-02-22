<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 09:32
 */
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$country = \common\models\db\SystemCountry::findOne($faker->numberBetween(1,100));
$currency = \common\models\db\SystemCurrency::findOne($faker->numberBetween(1,100));
return [
    'id' => $id,
    'country_id' => $country->id,
    'locale' => $country->country_code,
    'name' => $country->name,
    'country_name' => $country->name,
    'address' => $faker->address,
    'url' => $faker->url,
    'currency' => $currency->name,
    'currency_id' => $currency->id,
    'status' => $faker->numberBetween(0,1),
    'env' => $faker->randomKey(['ENV','UAT','DEV']),
];