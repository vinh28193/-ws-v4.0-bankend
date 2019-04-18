<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionRequest extends BaseObject
{

    /**
     * @var Promotion
     */
    public $promotion;

    /**
     * @var string
     */
    public $paymentService;

    /**
     * @var Order[]
     */
    public $orders;
    public $totalAmount;

    public $totalFeeValid = 0;
    public $totalValidAmount = 0;

    public $discountOrders = [];
    public $totalDiscountAmount = 0;

}