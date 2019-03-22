<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 20:42
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$packages = [
    ['id' => 1, 'package_code' => 'PK_1223454321'],
    ['id' => 2, 'package_code' => 'PK_0987654323'],
    ['id' => 3, 'package_code' => 'PB_0000000000'],
];

$manifests = [
    ['id' => 1, 'manifest_code' => 'RVS789'],
    ['id' => 2, 'manifest_code' => 'VA1234'],
    ['id' => 3, 'manifest_code' => 'HNC555'],
];
$package = $faker->randomElement($packages);
$manifest = $faker->randomElement($manifests);
$manifest['manifest_code'] .= rand(1000,9999);
$id = $index + 1;
return [
    'id' => $id,
    'version'=>'4.0',
    'store_id' => 1,
    'manifest_id' => $manifest['id'],
    'manifest_code' => $manifest['manifest_code'],
    'package_id' => $package['id'],
    'package_code' => $package['package_code'],
    'package_item_id' => null,
    'weshop_tag' => \common\helpers\WeshopHelper::generateTag($index,'WS',16),
    'order_ids' => null,
    'tracking_code' => \common\helpers\WeshopHelper::generateTag($index,'TK',16),
    'weight' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'quantity' => $faker->numberBetween(1,99),
    'dimension_width' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'dimension_length' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'dimension_height' => \common\helpers\WeshopHelper::roundNumber($faker->numberBetween(100,9999),2),
    'warehouse_alias' => $faker->randomElement(['BMVN_HN', 'BMVN_HCM','WS_HN']),
    'warehouse_tag' => \common\helpers\WeshopHelper::generateTag($index,'CBTAG_',16),
    'warehouse_note' => $faker->realText(100),
    'warehouse_status' => $faker->randomElement(['open','close', null]),
    'operation_note' =>  $faker->realText(100),
    'status' => 'NEW',
    'remove' => 0,
    'created_by' => 1,
    'created_at' =>  $faker->unixTime,
    'updated_by' => 1,
    'updated_at' =>  $faker->unixTime,
];
