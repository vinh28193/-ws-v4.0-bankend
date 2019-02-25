<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 10:55
 */

use common\fixtures\components\FixtureUtility;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;

$storeManager = Yii::$app->storeManager;
/** @var  $store \yii\db\ActiveRecord */
$store = $storeManager->store;
/** @var  $storeAdditionalFee \common\models\StoreAdditionalFee[]*/
$storeAdditionalFee = $store->storeAdditionalFee;

$order = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\order.php',null));
$feeType = $faker->randomElement($storeAdditionalFee);

$amountLocal = $faker->numberBetween(1000,100000000);
$amountLocal = round($amountLocal/1000) *1000;

return [
    'id' => $id,
    'store_id' => $storeManager->getId(),
    'order_id' => $order['id'],
    'type_fee' => $feeType['name'],
    'amount' => $faker->numberBetween(1,9999),
    'amount_local' => $amountLocal,
    'currency' => $faker->currencyCode
];