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
$data = [
    'Kho anh Lâm',
    'Kho RVS',
    'Kho VA',
    'Kho cũ',
    'Kho Box Me',
];
$id = $index + 1;
$province = $faker->randomElement(\common\fixtures\components\FixtureUtility::getProvincesByIdCountry(1));
$district = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDistrictsByIdProvince($province['id']));
$name  = $data[$index >4 ? rand(0,4) : $index];
return [
    'id' => $id,
    'version'=>'4.0',
    'name' => $name,
    'description' => $name,
    'district_id' => $district['id'],
    'province_id' => $province['id'],
    'country_id' => 1,
    'store_id' => 1,
    'address' => $faker->unique()->address,
    'type' => rand(1,2),
    'warehouse_group' => $index > 4 ? rand(1,3) : 3,
    'post_code' => $faker->unique()->postcode,
    'telephone' => $faker->unique()->phoneNumber,
    'email' => $faker->unique()->email,
    'contact_person' => "",
    'ref_warehouse_id' => $faker->unique()->randomNumber(),
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime(),
];
