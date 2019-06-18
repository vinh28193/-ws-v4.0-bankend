<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionRequest extends BaseObject
{

    public $totalAmount = 0;
    public $additionalFees = [];
    public $totalDiscountAmount = 0;
    public $discountFees = [];
}