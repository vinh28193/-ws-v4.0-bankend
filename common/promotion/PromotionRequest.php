<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionRequest extends BaseObject
{

    /**
     * @var string
     */
    public $paymentService;
    public $totalValidAmount = 0;
    public $discountOrders = [];
    public $totalDiscountAmount = 0;

}