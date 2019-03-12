<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 13:52
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$type_amount = $faker->randomElement(['percent','money']);
$dataUser = include '.\common\fixtures\data\system_district.php';
return [
    'id' => $id,
    'name' => $faker->text(50),
    'code' => strtoupper($faker->unique()->text(20)),
    'message' => $faker->realText(50),
    'type_coupon' => $type_coupon = $faker->randomElement(['REFUND','COUPON','PROMOTION']),
    'type_amount' => $type_amount,
    'store_id' => 1,
    'amount' => $amount = $type_amount == 'money' ? round($faker->numberBetween(1000,1000000),-3) : $faker->randomFloat(2,0,50.01),
    'percent_for' => $type_amount == 'money' ? "" : $faker->randomElement(['A8','A4']),
    'created_by' => $faker->numberBetween(0,count($dataUser) > 0 ? count($dataUser) : 10),
    'start_time' => $faker->unixTime,
    'end_time' => $faker->unixTime,
    'limit_customer_count_use' => $faker->numberBetween(0,10),
    'limit_count_use' => $count_limit = $faker->numberBetween(0,1000),
    'count_use' => $faker->numberBetween(0,$count_limit),
    'limit_amount_use' => round($faker->numberBetween(1000,$amount > 1000 ? $amount*100 : 10000000),-3),
    'limit_amount_use_order' => $amount > 1000 ? $amount : round($faker->numberBetween(1000, 500000),-3),
    'for_email' => $type_coupon =='PROMOTION' ? null : $faker->randomElement(\common\fixtures\components\FixtureUtility::getDataWithColumn('.\common\fixtures\data\customer.php','email')),
    'for_portal' => $faker->randomElement([null,'amazon','amazon-jp',null,null,'ebay']),
    'for_category' => null,
    'for_min_order_amount' => round($faker->numberBetween(500000,10000000),-3),
    'for_max_order_amount' => null,
    'total_amount_used' => round($faker->numberBetween(500000,10000000),-3) * 2,
    'used_first_time' => $faker->unixTime,
    'used_last_time' => $faker->unixTime,
    'can_use_instalment' => $faker->randomElement([0,0,1,0,0]),
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => $faker->randomElement([0,0,1,0,0]),
];