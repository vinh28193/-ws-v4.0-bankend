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
     * @var string ebay/amazon
     */
    public $itemType;

    /**
     * @var string|integer
     */
    public $categoryId;

    /**
     * @var integer
     */
    public $shippingQuantity;

    /**
     * @var integer
     */
    public $shippingWeight;

    /**
     * @var int
     */
    public $totalAmount;

    /**
     * @var int
     */
    public $totalDiscountAmount = 0;
}