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

$country = \common\models\db\SystemCountry::findOne($faker->numberBetween(1,10));
$currency = \common\models\db\SystemCurrency::findOne($faker->numberBetween(1,10));

// weshop-v4.back-end.local.vn
// [1, 'vi', 'Weshop Dev VN', ' Viet Nam', '18 Tam Trinh', 'weshop-v4.back-end.local.vn', 'VND', 1, 'dev']

$_store_fixed =  [
        'id' => 1,
        'country_id' => 'vi',
        'locale' => 'Weshop Dev VN',
        'name' => 'Viet Nam',
        'country_name' => 'Viet Nam',
        'address' => '18 Tam Trinh',
        'url' => 'weshop-v4.back-end.local.vn',
        'currency' => 'VND',
        'currency_id' =>1,
        'status' => 1,
        'env' => 'dev',
];

$_store = [
    'id' => $index + 1 ,
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



$store_array = array_merge( $_store_fixed , $_store );
return $store_array;