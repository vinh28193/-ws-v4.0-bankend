<?php


namespace common\promotion;

use common\models\Customer;
use common\products\BaseProduct;
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
     * @var array
     */
    public $cartIds;

    /**
     * @var string payment service
     * template "PaymentMethodId_PaymentBankCode"
     */
    public $paymentService;

    /**
     * @var integer|float
     */
    public $refundOrderId;

    /**
     * @var string|CartManager
     */
    protected $cartManager = 'cart';

    public function init()
    {
        parent::init();
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());
    }

    /**
     * @var string|Customer
     */
    private $_customer;

    /**
     * @return Customer|string|null
     */
    public function getCustomer()
    {
        if (!is_object($this->_customer)) {
            $this->_customer = Customer::findOne($this->customerId);
        }
        return $this->_customer;
    }

    /**
     * @param $customer
     */
    public function setCustomer($customer)
    {
        $this->_customer = $customer;
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
        $results = ['success' => false, 'message' => 'khong co truong trinh giam gia phu hop', 'data' => [], 'totalDiscount' => 0];
        if (!$this->validate()) {
            $results['message'] = $this->getFirstErrors();
            return $results;
        }
        $promotions = $this->findPromotion();
        $totalDiscount = 0;
        foreach ($this->cartIds as $cartId) {
            $detail = ['success' => false, 'message' => "can not get item form cart `$cartId`", 'data' => [], 'value' => 0];
            /** @var $product BaseProduct */
            if (($data = $this->cartManager->getItem($cartId)) === false || !($product = $data['response']) instanceof BaseProduct) {
                continue;
            }
            $additionalFees = [];
            foreach ($product->getAdditionalFees()->keys() as $key) {
                $additionalFees[$key] = $product->getAdditionalFees()->getTotalAdditionFees($key)[1];
            }

            $item = new PromotionItem([
                'customer' => $this->getCustomer(),
                'itemType' => $product->getItemType(),
                'customCategory' => $product->getCustomCategory(),
                'shippingQuantity' => $product->getShippingQuantity(),
                'shippingWeight' => $product->getShippingWeight(),
                'additionalFees' => $additionalFees,
                'paymentService' => $this->paymentService
            ]);
            $discountAmount = 0;
            foreach ($promotions as $promotion) {
                /** @var $promotion Promotion */
                if (($result = $promotion->checkCondition($item)) !== true && is_array($result) && count($result) === 2) {
                    $detail['data'][] = $result;
                } else {
                    $item = $promotion->calculatorDiscount($item);
                    $item->customer = null;
                    $discountAmount += $item->totalDiscountAmount;
                    $detail['data'][] = [
                        'fees' => $item->discountFees,
                        'detail' => $item->discountDetail,
                        'value' => $item->totalDiscountAmount
                    ];
                }

            }
            if ($discountAmount > 0) {
                $detail['success'] = true;
                $detail['message'] = "App dung thanh cong cho cart '$cartId'";
                $detail['value'] = $discountAmount;
            }
            $totalDiscount += $discountAmount;
            $results['data'][$cartId] = $detail;
        }
        if ($totalDiscount > 0) {
            $results['success'] = true;
            $results['message'] = "ap dung chuong trinh thanh cong";
            $results['totalDiscount'] = $totalDiscount;
        }
        return $results;
    }


    /**
     * @return Promotion[]|null
     */
    protected function findPromotion()
    {
        return Promotion::find()->all();
    }

}