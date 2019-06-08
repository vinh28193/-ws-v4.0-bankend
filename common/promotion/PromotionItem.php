<?php


namespace common\promotion;

use yii\base\BaseObject;

class PromotionItem extends BaseObject
{
    /**
     * @var string
     */
    public $paymentService;


    /**
     * @var int
     */
    public $totalAmount;

    /**
     * @var int
     */
    public $totalDiscountAmount = 0;
}