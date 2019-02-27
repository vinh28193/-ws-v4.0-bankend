<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 26/02/2019
 * Time: 14:06
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
    'name' => $faker->name,
    'description' => $faker->name,
    'district_id' => $district['id'],
    'province_id' => $province['id'],
    'country_id' => 1,
    'store_id' => 1,
    'address' => $faker->address,
    'type' => 2,
    'warehouse_group' => 1,
    'post_code' => $faker->postcode,
    'telephone' => $faker->phoneNumber,
    'email' => 'Email',
    'contact_person' => "",
    'ref_warehouse_id' => $faker->randomNumber(),
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime(),
];