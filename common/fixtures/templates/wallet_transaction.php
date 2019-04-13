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
$order = $faker->randomElement(\common\fixtures\components\FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\order.php',null));

return [
    'id' => $id,
    'version'=>'4.0',
    'transaction_code' =>$faker->md5(),
    'transaction_status' => 'DONE',
    'transaction_type' => 'PAYMENT',
    'customer_id' => 1,
    'order_id' =>$faker->numberBetween(300,999), // $order['id'],
    'transaction_amount_local' => $faker->numberBetween(1000000,100000000),//$order['total_final_amount_local'],
    'transaction_description' => 'Thanh toán đơn hàng',
    'note' => 'Giao dich thanh công',
    'transaction_reference_code' => $faker->md5(),
    'third_party_transaction_code' => $faker->md5(),
    'third_party_transaction_link' => 'https://nganluong.vn/'.$faker->postcode.'/'.$order['Ordercode'].'.html',
    'third_party_transaction_status' => 'success',
    'third_party_transaction_time' => $faker->unixTime(),
    'before_transaction_amount_local' => $order['total_final_amount_local'],
    'after_transaction_amount_local' => $order['total_final_amount_local'],
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime(),
];
