<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-02
 * Time: 14:12
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
$id = $index + 1;

$image = $faker->imageUrl();

return [
    'id' => 'ID',
    'store_id' => $id,
    'base_path' => null,
    'name' => null,
    'full_path' => $image,
    'width' => 640,
    'height' => 480,
    'quality' => 80,
    'size' => 2000,
    'type' => 'image/jpeg',
    'reference' => null,
    'reference_id' => null,
    'is_uploaded' => 0,
    'status' => 1,
    'uploaded_by' => 1,
    'uploaded_at' => $faker->unixTime,
    'uploaded_from_ip' => $faker->localIpv4,
];