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

$faker = \Faker\Factory::create('vi_VN');
$province = $faker->randomElement(\common\fixtures\components\FixtureUtility::getProvincesByIdCountry($IdCountry = 1));
$district = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDistrictByIdCountry($IdCountry = 1,$province['country_id']));

//var_dump($province);
//var_dump($district);
//die;

return [
    'id' => $index + 1,
    'version'=>'4.0',
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
    'email' => $faker->email,
    'phone' => $faker->phoneNumber,
    'country_id' => 1,
    'country_name' => 'Việt Nam',
    'province_id' => $faker->unique()->numberBetween(1,100), //$province['id'],
    'province_name' => $province['name'],
    'district_id' => $district['id'],
    'district_name' => $district['name'],
    'address' => $faker->address,
    'post_code' => $faker->postcode,
    'store_id' => 1,
    'type' => 1,
    'is_default' => 0,
    'customer_id' => $faker->numberBetween(1,1500),
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'remove' => $faker->numberBetween(0,1),
];
