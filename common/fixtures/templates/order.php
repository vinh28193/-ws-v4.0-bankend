<?php
use common\fixtures\components\FixtureUtility;
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:57
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$customer = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\customer.php',null));
$address = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\address.php',null));
$user = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\user.php',null));
$coupon = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\coupon.php',null,['type_coupon' => ['COUPON','REFUND'],'remove' => 0]));
//$promotion = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\coupon.php',['type_coupon' => 'PROMOTION']));
$product = FixtureUtility::getDataWithColumn('.\common\fixtures\data\product.php',null,['order_id'=>$id]);
if($product){
    $seller = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\seller.php',null,['id'=>$product[0]['seller_id']]));
}else{
    $seller = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\seller.php',null));
}

return [
    'id' => $id,
    'store_id' => 1,
    'type_order' => $typeOrder = $faker->randomElement(['SHOP','REQUEST','POS','SHIP']),
    'portal' => $seller['portal'],
    'is_quotation' => $is_quotation = $typeOrder = 'REQUEST' ? 1 : $typeOrder == 'SHOP' ? $faker->randomElement([0,0,0,0,0,0,0,0,00,0,1]): 0,
    'quotation_status' => $is_quotation == 0 ? "" : $faker->randomElement(['APPROVE',"DECLINE","NEW"]),
    'quotation_note' => $is_quotation == 0 ? "" : $faker->realText(50),
    'customer_id' => $customer['id'],
    'receiver_email' => $address['email'],
    'receiver_name' => $address['last_name']." ".$address['first_name'],
    'receiver_phone' => $address['phone'],
    'receiver_address' => $address['address'],
    'receiver_country_id' => $address['country_id'],
    'receiver_country_name' => $address['country_name'],
    'receiver_province_id' => $address['province_id'],
    'receiver_province_name' => $address['province_name'],
    'receiver_district_id' => $address['district_id'],
    'receiver_district_name' => $address['district_name'],
    'receiver_post_code' => $address['post_code'],
    'receiver_address_id' => $address['id'],
    'note_by_customer' => $faker->realText(50),
    'note' => $faker->realText(50),
    'payment_type' => 'WALLET',
    'sale_support_id' => $user['id'],
    'support_email' => $user['email'],
    'coupon_id' => $coupon_id = $faker->randomElement([null,null,null,null,null,null,null,null,null,$coupon['id']]),
    'coupon_code' => $coupon_id ? $coupon['code'] : null,
    'coupon_time' => $faker->unixTime(),
    'revenue_xu' => 0,
    'xu_count' => 0,
    'xu_amount' => 0,
    'is_email_sent' => 0,
    'is_sms_sent' => 0,
    'total_quantity' => $product? FixtureUtility::getSumArray($product,'quantity') : 0,
    'promotion_id' => null,
    'difference_money' => 0,
    'utm_source' => null,
    'seller_id' => $seller['id'],
    'seller_name' => $seller['name'],
    'seller_store' => $seller['link_store'],
    'total_final_amount_local' => 0,
    'total_paid_amount_local' => 0,  //0
    'total_refund_amount_local' => 0,  //0
    'total_amount_local' => $product?FixtureUtility::getSumArray($product,'total_price_amount_local'):0,
    'total_fee_amount_local' => 0,
    'total_counpon_amount_local' => 0,
    'total_promotion_amount_local' => 0,
    'total_price_amount_local' => 0,
    'total_tax_us_amount_local' => 0,
    'total_shipping_us_amount_local' => 0,
    'total_weshop_fee_amount_local' => 0,
    'total_intl_shipping_fee_amount_local' => 0,
    'total_custom_fee_amount_local' => 0,
    'total_delivery_fee_amount_local' => 0,
    'total_packing_fee_amount_local' => 0,
    'total_inspection_fee_amount_local' => 0,
    'total_insurance_fee_amount_local' => 0,
    'total_vat_amount_local' => 0,
    'exchange_rate_fee' => $product ? $product[0]['exchange_rate'] : 0,
    'exchange_rate_purchase' => 0,
    'currency_purchase' => 0,
    'purchase_order_id' => null,
    'purchase_transaction_id' => null,
    'purchase_amount' => null,
    'purchase_account_id' => null,
    'purchase_account_email' => null,
    'purchase_card' => null,
    'purchase_amount_buck' => null,
    'purchase_amount_refund' => null,
    'purchase_refund_transaction_id' => null,
    'total_weight' => null,
    'total_weight_temporary' => null,
    'new' => $faker->unixTime(),
    'purchased' => null,
    'seller_shipped' => null,
    'stockin_us' => null,
    'stockout_us' => null,
    'stockin_local' => null,
    'stockout_local' => null,
    'at_customer' => null,
    'returned' => null,
    'cancelled' => null,
    'lost' => null,
    'current_status' => "NEW",
    'created_time' => $faker->unixTime,
    'updated_time' => $faker->unixTime,
    'remove' => 0,
];