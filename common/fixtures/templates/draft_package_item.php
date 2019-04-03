<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:29
 */

use common\fixtures\components\FixtureUtility;
use common\helpers\WeshopHelper;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
$id = $index + 1;

$manifests = [
    ['id' => 1, 'manifest_code' => 'RVS789'],
    ['id' => 2, 'manifest_code' => 'VA1234'],
    ['id' => 3, 'manifest_code' => 'HNC555'],
];
$manifest = $faker->randomElement($manifests);
$invoice = WeshopHelper::generateTag($index, '', 26);
$tk = WeshopHelper::generateTag($index, 'TK', 16);
$order = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\order.php', null))['id'];
$product = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\product.php', null, ['order_id' => $order]))['id'];
return [
    'id' => $id,
    'tracking_code' => $tk,
    'product_id' => $product,
    'order_id' => $order,
    'quantity' => $faker->numberBetween(1, 100),
    'weight' => $faker->numberBetween(1, 100),
    'dimension_l' => $faker->numberBetween(1, 100),
    'dimension_w' => $faker->numberBetween(1, 100),
    'dimension_h' => $faker->numberBetween(1, 100),
    'manifest_id' => $manifest['id'],
    'manifest_code' => $manifest['manifest_code'],
    'purchase_invoice_number' => $invoice,
    'status' => 1,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'created_by' => 1,
    'updated_by' => 1,
];