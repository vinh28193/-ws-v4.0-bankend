<?php


namespace common\promotion;

use common\models\Customer;
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
    public $refundOrderId;

    /**
     * @var integer
     */
    public $totalAmount;
    /**
     * @var Order[]
     */
    private $_orders;

    public $success = false;

    public $message = 'Không có trương trình giảm giá';

    private $_detail = [];

    public function addDetail($detail)
    {
        $this->_detail[] = $detail;
    }

    public $debug = [];

    /**
     * @param $orders
     */
    public function setOrders($orders)
    {
        foreach ($orders as $key => $order) {
            $this->_orders[$key] = new Order($order);
        }
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

    public function loadParam($params)
    {
        $params = require 'mock-post.php';
//        foreach ($params as $name => $value) {
//            $this->$name = $value;
//        }
        return parent::load($params, '');
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
            [['couponCode', 'customerId', 'paymentService', 'totalAmount', 'orders'], 'safe']
        ];
    }


    public function checkPromotion()
    {
        if (!$this->validate()) {
            $result['message'] = $this->getFirstErrors();
            return $result;
        }

        $response = new PromotionResponse();
        foreach ($this->findPromotion() as $key => $promotion) {
            /** @var $promotion Promotion */
            $request = new PromotionRequest();

            foreach ($this->_orders as $index => $order) {
                /** @var  $order PromotionItem */
                $passed = $promotion->checkCondition($order);
                if ($passed !== true && is_array($passed) && count($passed) === 2) {
                    $response->errors[$index][$promotion->code] = $passed[1];
                    continue;
                }
                $request->discountOrders[$index] = $order;
            }
            if (count($request->discountOrders) > 0) {
                $promotion->calculatorDiscount($request, $response);
                $response->success = true;
                $response->message = 'app dụng trương chình thành công';
            }
        }
        if (($coupon = $this->findCoupon()) !== null) {
            $request = new PromotionRequest();

            foreach ($this->_orders as $index => $order) {
                /** @var  $order PromotionItem */
                $passed = $coupon->checkCondition($order);
                if ($passed !== true && is_array($passed) && count($passed) === 2) {
                    $response->errors[$index][$coupon->code] = $passed[1];
                    continue;
                }
                $request->discountOrders[$index] = $order;
            }
            if (count($request->discountOrders) > 0) {
                $coupon->calculatorDiscount($request, $response);
                $response->success = true;
                $response->message = $coupon->message;
            }
        }
        return $response;
    }

    /**
     * @return Promotion|null
     */
    protected function findCoupon()
    {
        return Promotion::find()->where([
            'AND',
            ['code' => $this->couponCode],
            ['type' => Promotion::TYPE_COUPON],
            ['status' => 1]
        ])->one();
    }

    /**
     * @return Promotion[]|array
     */
    protected function findPromotion()
    {
        return Promotion::find()->where([
            'AND',
            ['type' => Promotion::TYPE_PROMOTION],
            ['status' => 1]
        ])->all();
    }

}