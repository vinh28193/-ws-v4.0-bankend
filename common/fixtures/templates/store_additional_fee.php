<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 09:28
 */

use yii\helpers\Inflector;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$feeNames = [
    'product_price_origin', 'tax_fee_origin', 'origin_shipping_fee',
    'weshop_fee', 'intl_shipping_fee', 'custom_fee',
    'delivery_fee_local', 'packing_fee', 'inspection_fee', 'insurance_fee', 'vat_fee',
];

$conditions = [
    'product_price_origin' => 'common\components\conditions\SimpleCondition',
    'tax_fee_origin' => 'common\components\conditions\OriginTaxCondition',
    'origin_shipping_fee' => 'common\components\conditions\OriginShippingFeeCondition',
    'weshop_fee' => 'common\components\conditions\StoreFeeCondition',
    'intl_shipping_fee' => 'common\components\conditions\InternationalShippingFeeCondition',
    'custom_fee' => 'common\components\conditions\CustomFeeCondition',
    'delivery_fee_local' => 'common\components\conditions\LocalDeliveryFeeCondition',

];
$id = $index + 1;
$feeName = $faker->randomElement($feeNames);
$condition = isset($conditions[$feeName]) ? $conditions[$feeName]  : 'common\components\conditions\SimpleCondition';
$condition = new $condition;
return [
    'id' => $id,
    'store_id' => 1,
    'name' => $feeName,
    'label' => Inflector::camel2words($feeName),
    'currency' => $faker->randomElement(['USD','VND','IDR']),
    'description' => $faker->realText(100),
    'condition_name' => $condition->name,
    'condition_data' => serialize($condition),
    'is_convert' => $faker->numberBetween(0,1),
    'is_read_only' => $faker->numberBetween(0,1),
    'status' => $faker->numberBetween(0,1),
    'created_by' => $faker->numberBetween(1,99),
    'created_time' => $faker->unixTime,
    'updated_by' => $faker->numberBetween(1,99),
    'updated_time' => $faker->unixTime,
    'fee_rate' => $faker->randomFloat(2,1,99),
];
