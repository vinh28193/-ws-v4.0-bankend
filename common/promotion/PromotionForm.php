<?php


namespace common\promotion;

use Yii;
use yii\base\Model;
use yii\di\Instance;
use common\components\cart\CartManager;

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
abstract class PromotionForm extends Model
{

    /**
     * @var string mã coupon
     */
    public $couponCode;

    /**
     * @var integer
     */
    public $customerId;

    /**
     * @inheritDoc
     */
    public function getFirstErrors()
    {
        return parent::getFirstErrors();
    }

    public function attributes()
    {
        return [

        ];
    }

    public function rules()
    {
        return [
            [['couponCode', 'customerId', 'paymentService', 'totalAmount'], 'safe']
        ];
    }

    public function checkPromotion()
    {
        if (!$this->validate()) {
            return PromotionResponse::create(false, $this->getFirstErrors(), $this->couponCode);
        }
        if (($promotion = $this->findPromotion()) === null) {
            return PromotionResponse::create(false, "Not found promotion '$this->couponCode'", $this->couponCode);
        }
        if (($request = $this->createRequest()) === false) {
            if ($this->hasErrors()) {
                return PromotionResponse::create(false, $this->getFirstErrors());
            }
            return PromotionResponse::create(false, "can not use {$promotion->code} because have unknown error");
        }
        if (($result = $promotion->checkCondition($request)) !== true && is_array($result) && count($result) === 2) {
            return PromotionResponse::create($result[0],  $result[1]);
        }
        $discount = $promotion->calculatorDiscount($request);
        return $discount;
    }


    /**
     * @return Promotion|null
     */
    protected function findPromotion()
    {
        return new Promotion();
    }

    /**
     * @return false|PromotionRequest
     */
    abstract protected function createRequest();
}