<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-16
 * Time: 08:54
 */

use common\fixtures\components\FixtureUtility;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$array = [
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
    FixtureUtility::getRandomCode(6),
];
$tags = $faker->randomElements($array, $faker->numberBetween(1, count($array) - 1));
$tags = implode(',', $tags);
return [
    'id' => $id,
    'version'=>'4.0',
    'shipment_code' => FixtureUtility::getRandomCode(6),
    'warehouse_tags' => $tags,
    'total_weight' => $faker->numberBetween(1000, 100000),
    'warehouse_send_id' => $faker->numberBetween(1, 99),
    'customer_id' => $faker->numberBetween(1, 99),
    'receiver_email' => $faker->email,
    'receiver_name' => $faker->name,
    'receiver_phone' => $faker->phoneNumber,
    'receiver_address' => $faker->address,
    'receiver_country_id' => 1,
    'receiver_country_name' => 'Viet Nam',
    'receiver_province_id' => $faker->numberBetween(1, 65),
    'receiver_province_name' => $faker->city,
    'receiver_district_id' => $faker->numberBetween(1, 99),
    'receiver_district_name' => $faker->city,
    'receiver_post_code' => $faker->postcode,
    'receiver_address_id' => $faker->numberBetween(1, 99),
    'note_by_customer' => $faker->realText(100),
    'note' => $faker->realText(200),
    'shipment_status' => $faker->randomElement(['NEW', 'SENT', 'PICKUP', 'RETURN']),
    'total_shipping_fee' => $faker->numberBetween(10000, 1000000),
    'total_price' => $faker->numberBetween(10000, 1000000),
    'total_cod' => $faker->numberBetween(10000, 1000000),
    'total_quantity' => $faker->numberBetween(10000, 1000000),
    'is_hold' => $faker->numberBetween(0, 1),
    'courier_code' => $faker->randomElement(['EMS', 'VTP', 'GHTK', 'SHIP60']),
    'courier_logo' => $faker->imageUrl(150, 150),
    'courier_estimate_time' => $faker->unixTime,
    'list_old_shipment_code' => $faker->randomElement([null, FixtureUtility::getRandomCode(6)]),
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
];
