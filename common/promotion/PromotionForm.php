<?php


namespace common\promotion;

use yii\base\Model;

/**
 * $post given
 *  $_POST = [
 *      string $couponCode 'COUPON_TEST'
 *      string $paymentMethod 'VCB';
 *      string $paymentProvider;//
 *      string $customerId
 *      array $categories (alias ex: [9999,8754])
 *      array $weights  (ex: [12,4])
 *      array $quantity (ex: [1,1])
 *      array $portal (ex: ['ebay','ebay'] or ['ebay','amazon])
 *      array additionalFees // khó
 *  ]
 * Class PromotionForm
 * @package common\promotion
 */
class PromotionForm extends Model
{

}