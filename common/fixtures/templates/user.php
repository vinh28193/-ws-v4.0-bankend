<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 08:46
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$pass = "$id@123456789";

return [
    'id' => $id,
    'username' => $faker->userName,
    'auth_key' => \Yii::$app->security->generateRandomString(),
    'password_hash' => \Yii::$app->security->generatePasswordHash($pass),
    'password_reset_token' => \Yii::$app->security->generateRandomString(),
    'email' => $faker->email,
    'status' => $faker->numberBetween(0,1),
    'created_at' => $faker->unixTime,
    'updated_at' => $faker->unixTime,
];