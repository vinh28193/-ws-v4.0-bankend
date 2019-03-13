<?php
/*
Faker\Provider\en_US\Address
cityPrefix                          // 'Lake'
secondaryAddress                    // 'Suite 961'
state                               // 'NewMexico'
stateAbbr                           // 'OH'
citySuffix                          // 'borough'
streetSuffix                        // 'Keys'
buildingNumber                      // '484'
city                                // 'West Judge'
streetName                          // 'Keegan Trail'
streetAddress                       // '439 Karley Loaf Suite 897'
postcode                            // '17916'
address                             // '8888 Cummings Vista Apt. 101, Susanbury, NY 95473'
country                             // 'Falkland Islands (Malvinas)'
latitude($min = -90, $max = 90)     // 77.147489
longitude($min = -180, $max = 180)  // 86.211205
*/
require __DIR__ . '/../../vendor/autoload.php';

$faker = \Faker\Factory::create('vi_VN');

//$faker->streetAddress
//echo $faker->cityPrefix; echo "</pre>";
//echo $faker->secondaryAddress; echo "</pre>";
//echo $faker->cityPrefix      ; echo "</pre>";                    // 'Lake'
//echo $faker->secondaryAddress  ; echo "</pre>";                  // 'Suite 961'
//echo $faker->state            ; echo "</pre>";                   // 'NewMexico'
//echo $faker->stateAbbr     ; echo "</pre>";                      // 'OH'



var_dump($faker->citySuffix);   // 'Ville'
var_dump($faker->streetSuffix);   // string(5) "Street"
var_dump($faker->buildingNumber); // string(1) "4"
var_dump($faker->city);           // string(12) "Hải Phòng"
var_dump($faker->streetName);     // string(17) "167 Phố Đổng"
var_dump($faker->streetAddress);  // string(25) "Phố Hàng Châu Khuyên"
var_dump($faker->postcode);   // string(10) "36730-2184"
var_dump($faker->address);   // string(78) "3068 Phố Lân, Xã Tiêu Chính Đức, Quận Vi Trầm Lễ Hồ Chí Minh"
var_dump($faker->country);    // 'Falkland Islands (Malvinas)'
var_dump($faker->latitude($min = -90, $max = 90));
var_dump($faker->longitude($min = -180, $max = 180));  // 86.211205