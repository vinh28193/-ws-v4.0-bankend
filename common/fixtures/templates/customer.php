<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 11:41
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$pass = "$id@123456789";

return [
    'id' => $id,
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
    'email' => $faker->email,
    'phone' => $faker->phoneNumber,
    'user_name' => $faker->userName,
    'password' => Yii::$app->security->generatePasswordHash($pass),
    'gender' => $faker->numberBetween(0,2),
    'birthday' => $faker->date('Y-m-d','2000-01-01'),
    'avatar' => $faker->imageUrl(),
    'link_verify' => $faker->url,
    'email_verified' => $faker->numberBetween(0,1),
    'phone_verified' => $faker->numberBetween(0,1),
    'last_order_time' => $faker->unixTime,
    'note_by_employee' => $faker->realText(200),
    'type_customer' => $faker->numberBetween(1,2),
    'access_token' => Yii::$app->security->generateRandomString(),
    'auth_client' => Yii::$app->security->generateRandomString(),
    'verify_token' => Yii::$app->security->generateRandomString(),
    'reset_password_token' => Yii::$app->security->generateRandomString(),
    'store_id' => 1,
    'active_shipping' => $faker->numberBetween(0,1),
    'total_xu' => 0,
    'total_xu_start_date' => "",
    'total_xu_expired_date' => "",
    'usable_xu' => 0,
    'usable_xu_start_date' => "",
    'usable_xu_expired_date' => "",
    'last_use_xu' => 0,
    'last_use_time' => "",
    'last_revenue_xu' => 0,
    'last_revenue_time' => "",
    'verify_code' => $faker->text(5),
    'verify_code_expired_at' => $faker->unixTime,
    'verify_code_count' => $faker->numberBetween(1,5),
    'verify_code_type' => "",
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'active' => $faker->numberBetween(0,1),
    'remove' => $faker->numberBetween(0,1),
];