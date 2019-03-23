<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-23
 * Time: 10:29
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use common\fixtures\components\FixtureUtility;

$storeAdditionalFee = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\store_additional_fee.php', $columnName = null));

$order =  $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\order.php', $columnName = null));
$product =  $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\product.php', $columnName = null));

$id = $index + 1;
return [
    'id' => $id,
    'order_id' => $order['id'],
    'product_id' => $product['id'],
    'type' => $storeAdditionalFee['name'],
    'name' => $storeAdditionalFee['label'],
    'amount' => $faker->numberBetween(100,999),
    'local_amount' => FixtureUtility::randFee(),
    'discount_amount' => FixtureUtility::randFee(),
    'currency' => $storeAdditionalFee['currency'],
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'remove' => 0
];