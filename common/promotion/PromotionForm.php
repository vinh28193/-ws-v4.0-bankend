<?php


namespace common\promotion;

use Yii;
use common\models\Customer;
use common\products\BaseProduct;
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
        $result = [
            'success' => false,
            'message' => 'Không có chương trình giảm giá phù hợp',
            'detail' => [],
            'discount' => 0
        ];
        if (!$this->validate()) {
            $result['message'] = $this->getFirstErrors();
            return $result;
        }

        $response = new PromotionResponse();
        $test = [];
        foreach ($this->findPromotion() as $key => $promotion) {
            /** @var $promotion Promotion */
            $request = new PromotionRequest();
            $request->promotion = $promotion;
            $request->orders = $this->_orders;
            $test[$promotion->code]['count'] = count($request->orders);
            foreach ($request->orders as $index => $order) {
                /** @var  $order PromotionItem */
                $passed = $promotion->checkCondition($order);
                $test[$promotion->code]['orders']["order $index"] = $passed;
                if ($passed !== true && is_array($passed) && count($passed) === 2) {
                    continue;
                }
                $request->discountOrders[$index] = $order;
                $test[$promotion->code]['passed'][] = $index;
            }
            $promotion->calculatorDiscount($request, $response);
        }
        var_dump($response->requests);
        die;
        return $response;

//        $promotions = $this->findPromotion();
//        foreach ($this->_orders as $index => $order) {
//            $order->paymentService = $this->paymentService;
//            foreach ($promotions as $promotion) {
//                $this->debug[] = "promotion `{$promotion->code}`";
//                if ($promotion->allow_order) {
//                    /** @var $promotion Promotion */
//                    $passed = $promotion->checkCondition($order);
//                    $this->debug[] = "check for order";
//                    if ($passed !== true && is_array($passed) && count($passed) === 2) {
//                        $result['detail'][] = $order;
//                        continue;
//                    }
//                    $this->debug[] = "passed promotion '$promotion->code'";
//                    $order = $promotion->calculatorDiscount($order);
//                    if ($order->totalDiscountAmount > 0) {
//                        $result['success'] = true;
//                        $result['detail'][] = $order;
//                    }
//                } else {
//                    $this->debug[] = "check for 3 product";
//                    foreach ($order->getProducts() as $index => $product) {
//                        /** @var $promotion Promotion */
//                        $passed = $promotion->checkCondition($product);
//                        $this->debug[] = "checking product $index";
//                        if ($passed !== true && is_array($passed) && count($passed) === 2) {
//                            continue;
//                        }
//                        $product = $promotion->calculatorDiscount($product);
//                        $order->updateProduct($index, $product);
//                        $result['detail'][] = $order;
//                    }
//                }
//            }
//        }
//        var_dump($result);
//        die;
//        return $result;
    }


    /**
     * @return Promotion[]|null
     */
    protected function findPromotion()
    {
        return Promotion::find()->all();
    }

}