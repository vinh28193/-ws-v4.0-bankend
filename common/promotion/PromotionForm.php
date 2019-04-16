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

    /**
     * @var string mã coupon
     */
    public $couponCode;

    /**
     * @var integer
     */
    public $customerId;
    /**
     * @var string payment service
     * template "PaymentMethodId_PaymentBankCode"
     */
    public $paymentService;

    /**
     * @var integer|float
     */
    public $totalAmount;
    /**
     * check promotion
     */


    /**
     * @inheritDoc
     */
    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
//            foreach ($this->items as $key => $item) {
//                $this->items[$key] = new PromotionItem($item);
//            }
            return true;
        }
        return false;
    }

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
            'couponCode', 'customerId', 'paymentService', 'totalAmount'
        ];
    }

    public function checkPromotion()
    {

    }

    /**
     * calculate discount amount
     */
    public function calculatePromotion()
    {

    }

    protected function findPromotion()
    {

    }
}