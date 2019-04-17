<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionItem extends BaseObject
{

    public $customer;

    public $paymentService;

    public $itemType;

    public $customCategory;

    public $shippingQuantity;

    public $shippingWeight;

    public $additionalFees;

    public $discountFees = [];

    public $discountDetail = [];

    public $totalDiscountAmount = 0;
}