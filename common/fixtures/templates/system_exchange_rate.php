<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-01
 * Time: 11:07
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
/** @var  $exRate  \common\components\ExchangeRate */
$exRate = Yii::$app->exRate;

$currencies = $exRate->currencies;

$from = $faker->randomElement(['USD','JPY','GBP']);
$to = $faker->randomElement($currencies);

return [
    'store_id' => 1,
    'form' => $from,
    'to' => $to,
    'rate' => $from === $to ? 1 : $faker->numberBetween(1000, 100000),
    'sync_at' => $faker->unixTime
];
