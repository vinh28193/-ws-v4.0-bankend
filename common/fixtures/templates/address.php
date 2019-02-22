<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:53
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$province = $faker->randomElement(\common\fixtures\components\FixtureUtility::getProvincesByIdCountry(1));
$district = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDistrictsByIdProvince($province['id']));
return [
    'id' => $id,
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
    'email' => $faker->email,
    'phone' => $faker->phoneNumber,
    'country_id' => 1,
    'country_name' => 'Việt Nam',
    'province_id' => $province['id'],
    'province_name' => $province['name'],
    'district_id' => $district['id'],
    'district_name' => $district['name'],
    'address' => $faker->address,
    'post_code' => $faker->postcode,
    'store_id' => 1,
    'type' => 1,
    'is_default' => 0,
    'customer_id' => $faker->numberBetween(1,1500),
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => $faker->numberBetween(0,1),
];