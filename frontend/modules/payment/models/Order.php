<?php


namespace frontend\modules\payment\models;

use common\additional\AdditionalFeeCollection;
use common\additional\AdditionalFeeInterface;
use common\additional\AdditionalFeeTrait;
use common\components\cart\CartHelper;
use common\components\cart\CartSelection;
use common\helpers\WeshopHelper;
use common\models\Category;
use common\models\db\TargetAdditionalFee;
use common\models\Order as BaseOrder;
use common\models\Seller;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;

class Order extends BaseOrder implements AdditionalFeeInterface
{

    public $cartId;
    public $checkoutType;
    public $uuid;
    public $courierDetail = [];
    public $acceptance_insurance = 'N';
    public $courier_sort_mode = 'best_rating';
    public $_additionalFees;

    public $couponCode;
    public $discountDetail = [];
    public $discountAmount = 0;
    /**
     * @var
     */
    private $_cart;

    public function init()
    {
        parent::init();
    }

    /**
     * @return \common\components\cart\CartManager
     */
    public function getCart()
    {
        if (!is_object($this->_cart)) {
            $this->_cart = CartHelper::getCartManager();
        }
        return $this->_cart;
    }

    public function getAdditionalFees()
    {
        if ($this->_additionalFees === null) {
            $this->_additionalFees = new AdditionalFeeCollection();
        }
        return $this->_additionalFees;
    }

    public function setAdditionalFees($additionalFees)
    {
        foreach ($additionalFees as $key => $value) {
            $this->getAdditionalFees()->setDefault($key, $value);
        }

    }

    public function extraFields()
    {
        return ArrayHelper::merge(parent::extraFields(), ['cartId' => 'cartId', 'courierDetail', 'additionalFees' => 'additionalFees']);
    }

    /**
     * @return string
     */
    public function getUniqueCode()
    {
        return $this->ordercode;
    }

    public function getType()
    {
        return strtolower($this->portal);
    }

    /**
     * @return integer
     */
    public function getTotalOrigin()
    {
        return $this->total_price_amount_origin;
    }

    /**
     * @return null|Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getUserLevel()
    {
        /** @var $customer User */
        if (($customer = $this->customer) !== null) {
            return $customer->userLevel;
        }
        return User::LEVEL_NORMAL;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return false;
    }

    /**
     * @return boolean
     */
    public function getIsSpecial()
    {
        return false;
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
        return $this->total_weight_temporary;
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
        return $this->total_quantity;
    }

    /**
     * @return null|array|mixed
     */
    public function getShippingParams()
    {
        $weight = WeshopHelper::roundNumber((int)$this->total_weight_temporary * 1000);
        $totalAmount = $this->total_amount_local;
        $items = [];
        foreach ($this->products as $product) {
            $items[] = [
                'sku' => implode('|', [$product->parent_sku, $product->sku]),
                'label_code' => '',
                'origin_country' => '',
                'name' => $product->product_name,
                'desciption' => '',
                'weight' => WeshopHelper::roundNumber((int)$product->total_weight_temporary * 1000),
                'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
                'customs_value' => WeshopHelper::roundNumber($product->total_price_amount_local),
                'quantity' => $product->quantity_customer,
            ];
        }
        $parcel = [
            'weight' => $weight,
            'amount' => $totalAmount,
            'description' => $this->seller ? "order of seller `{$this->seller->seller_name}`" : "",
            'items' => $items
        ];
        $params = [
            'ship_from' => [
                'country' => 'US',
                'pickup_id' => ArrayHelper::getValue($this->getPickUpWareHouse(), 'ref_pickup_id')
            ],
            'ship_to' => [
                'contact_name' => $this->receiver_name,
                'company_name' => '',
                'email' => $this->receiver_email,
                'address' => $this->receiver_address,
                'address2' => '',
                'phone' => $this->receiver_phone,
                'phone2' => '',
                'country' => $this->getAdditionalFees()->storeManager->store->country_code,
                'province' => $this->receiver_province_id,
                'district' => $this->receiver_province_id,
                'zipcode' => $this->receiver_post_code,
                'tax_id' => '',
            ],
            'shipments' => [
                'content' => '',
                'total_parcel' => 1,
                'total_amount' => $totalAmount,
                'description' => '',
                'amz_shipment_id' => '',
                'chargeable_weight' => $weight,
                'parcels' => [$parcel]
            ],
        ];
        return $params;
    }

    /**
     * @var  array|\common\models\Warehouse|null
     */
    private $_pickUpWareHouse;

    /**
     * @return array|\common\models\Warehouse|null
     */
    public function getPickUpWareHouse()
    {
        if ($this->_pickUpWareHouse === null) {
            /** @var $user User */
            if (($user = $this->customer) !== null && $user->getPickupWarehouse() !== null) {
                $this->_pickUpWareHouse = $user->getPickupWarehouse();
            } elseif (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
                $current = $params['default'];
                $this->_pickUpWareHouse = ArrayHelper::getValue($params, "warehouses.$current");
            }
        }
        return $this->_pickUpWareHouse;
    }

    public function createPromotionParam()
    {
        $fees = [];
        foreach ($this->getAdditionalFees()->keys() as $key) {
            if ($key === 'tax_fee' || $key === 'shipping_fee') {
                continue;
            }
            $fees[$key] = $this->getAdditionalFees()->getTotalAdditionalFees($key)[1];
        }
        return [
            'couponCode' => $this->couponCode,
            'totalAmount' => $this->total_final_amount_local,
            'additionalFees' => $this->getAdditionalFees()->toArray(),
        ];
    }

    public function createOrderFromCart()
    {
        $type = $this->checkoutType;
//        if ($type === CartSelection::TYPE_INSTALLMENT) {
//            $type = CartSelection::TYPE_SHOPPING;
//        }
        $item = $this->getCart()->getItem($type, $this->cartId, $this->uuid);
        $params = $item['value'];
        if (($sellerParams = ArrayHelper::remove($params, 'seller')) === null || !is_array($sellerParams)) {
            $this->addError('cartId', 'Not found seller for order');
            return false;
        }
        $seller = new Seller($sellerParams);
        if (($productParams = ArrayHelper::remove($params, 'products')) === null || !is_array($productParams)) {
            $this->addError('cartId', 'Not found products for order');
            return false;
        }
        $supporter = ArrayHelper::remove($params, 'saleSupport');
        if ($supporter !== null) {
            $supporter = new User($supporter);
        }
        unset($params['customer']);
        unset($params['support_name']);
        $this->setAttributes($params);
        $products = [];
        $this->getAdditionalFees()->removeAll();
        foreach ($productParams as $key => $productParam) {
            if (($categoryParams = ArrayHelper::remove($productParam, 'category')) === null || !is_array($categoryParams)) {
                $this->addError('cartId', 'Not found category for product offset' . $key);
                return false;
            }
            $category = new Category([
                'alias' => ArrayHelper::getValue($categoryParams, 'alias'),
                'site' => ArrayHelper::getValue($categoryParams, 'site'),
                'origin_name' => ArrayHelper::getValue($categoryParams, 'origin_name'),
            ]);
            if (($productFeeParams = ArrayHelper::remove($productParam, 'additionalFees')) === null || !is_array($productFeeParams)) {
                $this->addError('cartId', 'Not found fee for product offset' . $key);
                return false;
            }
            // Collection Fee
            foreach ($productFeeParams as $name => $arrayFee) {
                if ($name === 'product_price') {
                    // exception, do not collect product_price
                    continue;
                }
                foreach ($arrayFee as $value) {
                    $this->getAdditionalFees()->add($name, $value);
                }
            }

            unset($productParam['available_quantity']);
            unset($productParam['quantity_sold']);
            $product = new Product($productParam);
            // product_price next to save, this fee not in collect
            if (!empty($productFeeParams)) {
                $fees = [];
                foreach ($productFeeParams as $name => $value) {
                    $value = reset($value);
                    $targetFee = new TargetAdditionalFee($value);
                    $targetFee->type = 'product';
                    $fees[] = $targetFee;
                }
                $product->populateRelation('productFees', $fees);
            }
            $product->populateRelation('category', $category);
            $products[$key] = $product;
        }
        $this->populateRelation('products', $products);
        $this->populateRelation('seller', $seller);
        $this->populateRelation('saleSupport', $supporter);
        if (!empty($this->courierDetail)) {
            $this->getAdditionalFees()->withCondition($this, 'international_shipping_fee', (int)ArrayHelper::getValue($this->courierDetail, 'total_fee', 0));
        }
        return $this;
    }

    public function removeCart()
    {
        return $this->getCart()->removeItem($this->checkoutType, $this->cartId, null, $this->uuid);
    }

    public function getTotalFinalAmount()
    {
        $totalAmount = $this->total_amount_local;
        Yii::info($this->getAdditionalFees()->toArray(), $totalAmount);
        $totalAmount += $this->getAdditionalFees()->getTotalAdditionalFees()[1];
        if ($this->discountAmount > 0) {
            $totalAmount -= $this->discountAmount;
        }
        return $totalAmount;
    }
}