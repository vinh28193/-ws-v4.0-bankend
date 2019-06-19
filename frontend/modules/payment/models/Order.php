<?php


namespace frontend\modules\payment\models;

use common\additional\AdditionalFeeCollection;
use common\additional\AdditionalFeeInterface;
use common\additional\AdditionalFeeTrait;
use common\helpers\WeshopHelper;
use common\models\Category;
use common\models\Order as BaseOrder;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;

class Order extends BaseOrder implements AdditionalFeeInterface
{

    public $cartId;
    public $courierDetail = [];
    public $acceptance_insurance = 'N';
    public $courier_sort_mode = 'best_rating';
    public $_additionalFees;

    public function getAdditionalFees()
    {
        if ($this->_additionalFees === null) {
            $this->_additionalFees = new AdditionalFeeCollection();
        }
        return $this->_additionalFees;
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
            return $customer->userLever;
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
                'parcels' => $parcel
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

}