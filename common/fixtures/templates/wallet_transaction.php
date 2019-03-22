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
    'transaction_code' => 'Transaction Code',
    'transaction_status' => 'Transaction Status',
    'transaction_type' => 'Transaction Type',
    'customer_id' => 'Customer ID',
    'order_id' => $order['id'],
    'transaction_amount_local' => 'Transaction Amount Local',
    'transaction_description' => 'Transaction Description',
    'note' => 'Note',
    'transaction_reference_code' => 'Transaction Reference Code',
    'third_party_transaction_code' => 'Third Party Transaction Code',
    'third_party_transaction_link' => 'Third Party Transaction Link',
    'third_party_transaction_status' => 'Third Party Transaction Status',
    'third_party_transaction_time' => 'Third Party Transaction Time',
    'before_transaction_amount_local' => 'Before Transaction Amount Local',
    'after_transaction_amount_local' => 'After Transaction Amount Local',
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime(),
];
