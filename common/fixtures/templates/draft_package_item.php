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
$order = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\order.php', null));
$order = $faker->randomElement([null, $order['id']]);
$product = null;
if ($order !== null) {
    $products = (FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\product.php', null, ['order_id' => $order]));
    if (!empty($products)) {
        $product = $faker->randomElement($products)['id'];
    }

}
return [
    'id' => $id,
    'tracking_code' => $tk,
    'product_id' => $product,
    'order_id' => $order,
    'quantity' => ($order && $product) ? $faker->numberBetween(1, 100) : null,
    'weight' => ($order && $product) ? $faker->numberBetween(1, 100) : null,
    'dimension_l' => ($order && $product) ? $faker->numberBetween(1, 100) : null,
    'dimension_w' => ($order && $product) ? $faker->numberBetween(1, 100) : null,
    'dimension_h' => ($order && $product) ? $faker->numberBetween(1, 100) : null ? $faker->numberBetween(1, 100) : null,
    'manifest_id' => $manifest['id'],
    'manifest_code' => $manifest['manifest_code'],
    'purchase_invoice_number' => ($order && $product) ? $invoice : null,
    'status' => 1,
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
    'created_by' => 1,
    'updated_by' => 1,
];