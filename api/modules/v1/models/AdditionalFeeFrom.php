<?php


namespace api\modules\v1\models;

use common\additional\AdditionalFeeCollection;
use common\additional\AdditionalFeeInterface;
use common\components\db\ActiveRecord;
use common\components\GetUserIdentityTrait;
use common\components\InternationalShippingCalculator;
use common\components\PickUpWareHouseTrait;
use common\components\UserCookies;
use common\helpers\WeshopHelper;
use common\models\Order;
use common\models\Product;
use common\models\Store;
use common\models\User;
use common\modelsMongo\ActiveRecordUpdateLog;
use common\products\BaseProduct;
use common\products\forms\ProductDetailFrom;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AdditionalFeeFrom extends Model implements AdditionalFeeInterface
{
    use PickUpWareHouseTrait;

    /**
     * @var string target name order/product
     */
    public $target_name;
    /**
     * @var string|integer
     */
    public $target_id;
    /**
     * @var string|integer
     */
    public $store_id;
    /**
     * @var null|string|integer
     */
    public $customer_id;

    /**
     * @var string type of target (ebay/amazon)
     */
    public $item_type;

    /**
     * Todo new Target 'gate'
     * @var string
     */
    public $item_id;

    /**
     * Todo new Target 'gate'
     * @var string
     */
    public $item_sku;

    /**
     * @var string
     */
    public $item_seller;
    /**
     * @var integer
     */
    public $shipping_weight;
    /**
     * @var integer
     */
    public $shipping_quantity;
    /**
     * @var null|string|integer
     */
    public $province;
    /**
     * @var null|string|integer
     */
    public $district;

    /**
     * @var string
     */
    public $post_code;

    /**
     * @var float
     */
    public $us_amount;
    /**
     * @var float
     */
    public $us_tax;
    /**
     * @var float
     */
    public $us_ship;
    /**
     * @var float
     */
    public $custom_fee;

    /**
     * @var bool
     */
    public $accept_insurance = 'N';


    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'target_name', 'target_id', 'store_id', 'customer_id', 'custom_fee', 'item_type', 'item_id', 'item_sku',
            'item_seller', 'shipping_weight', 'shipping_quantity', 'us_amount', 'us_tax', 'us_ship', 'accept_insurance'
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['target_name', 'target_id', 'store_id',], 'required'],
            [['target_id', 'store_id', 'customer_id', 'shipping_weight', 'shipping_quantity'], 'integer'],
            [['target_id', 'store_id', 'customer_id', 'shipping_weight', 'shipping_quantity'], 'filter', 'filter' => function ($value) {
                return (integer)$value;
            }],
            [['us_amount', 'us_tax', 'us_ship', 'custom_fee'], 'number'],
            [['target', 'item_type', 'item_id', 'item_sku', 'item_seller'], 'string'],
            [['province', 'district'], 'safe'],
            [['accept_insurance'], 'string']
        ]);
    }

    public function load($data, $formName = null)
    {
        return parent::load($data, $formName);
    }

    /** @var \common\components\db\ActiveRecord */
    private $_target;

    /**
     * @return \common\components\db\ActiveRecord
     */
    public function getTarget()
    {
        if (!$this->_target) {
            if ($this->target_name === 'gate') {
                $form = new ProductDetailFrom();
                $form->type = $this->item_type;
                $form->id = $this->item_id;
                $form->sku = $this->item_sku;
                $form->quantity = $this->getShippingQuantity();
                if (($product = $form->detail()) !== false) {
                    if ($this->item_seller !== null && $this->item_seller !== '') {
                        $product->updateBySeller($this->item_seller);
                    }
                    $this->_target = $product;
                }
            } else {
                $condition = ['id' => $this->target_id];
                $class = Product::className();
                if ($this->target_name == 'order') {
                    $condition = ['ordercode' => $this->target_id];
                    $class = Order::className();
                }
                if (($target = $class::findOne($condition)) !== null) {
                    ActiveRecordUpdateLog::register('beforeConfirm', $target);
                    $this->_target = $target;
                }
            }

        }
        return $this->_target;

    }

    private $_additionalFees;

    public function getAdditionalFees()
    {
        if ($this->_additionalFees === null) {
            $this->_additionalFees = new AdditionalFeeCollectionCustom();
            $this->_additionalFees->storeId = $this->store_id;
            $this->_additionalFees->userId = $this->customer_id;
            $this->_additionalFees->removeAll();
            if (($target = $this->getTarget()) !== null && $target instanceof ActiveRecord) {
                $this->_additionalFees->loadFormActiveRecord($this->getTarget(), $this->target_name);
            } elseif ($target instanceof BaseProduct) {

                $this->_additionalFees->fromArray($target->getAdditionalFees()->toArray());
            }
            $hasChange = false;
            $usAmount = $this->us_amount;
            $this->shipping_quantity = ($this->shipping_quantity !== null && $this->shipping_quantity !== '' && (int)$this->shipping_quantity > 0) ? $this->shipping_quantity : 1;
            Yii::info($usAmount && $usAmount !== '', (int)$this->shipping_quantity);
            if ($usAmount && $usAmount !== '') {

                $usAmount *= (int)$this->shipping_quantity;
            }
            if ($usAmount !== null && $usAmount !== '' && !WeshopHelper::compareValue($usAmount, $this->_additionalFees->getTotalAdditionalFees('product_price')[0])) {
                $this->_additionalFees->remove('product_price');
                $this->_additionalFees->withCondition($this, 'product_price', floatval($usAmount));
                $hasChange = true;
            }

            if ($this->us_ship !== null && $this->us_ship !== '' && !WeshopHelper::compareValue($this->us_ship, $this->_additionalFees->getTotalAdditionalFees('shipping_fee')[0])) {
                $this->_additionalFees->remove('shipping_fee');
                $this->_additionalFees->withCondition($this, 'shipping_fee', floatval($this->us_ship));
                $hasChange = true;
            }

            if ($this->us_tax !== null && $this->us_tax !== '' && !WeshopHelper::compareValue($this->us_tax, $this->_additionalFees->getTotalAdditionalFees('tax_fee')[0])) {
                $this->_additionalFees->remove('tax_fee');
                $this->_additionalFees->withCondition($this, 'tax_fee', floatval($this->us_tax));
                $hasChange = true;
            }

            if ($this->custom_fee !== null && $this->custom_fee !== '' && !WeshopHelper::compareValue($this->custom_fee, $this->_additionalFees->getTotalAdditionalFees('custom_fee')[0])) {
                $this->_additionalFees->remove('custom_fee');
                $this->_additionalFees->withCondition($this, 'custom_fee', floatval($this->custom_fee));
            }

            if ($hasChange || $totalOrigin = $this->getTotalOrigin() > 0) {
                $this->_additionalFees->remove('purchase_fee');
                $this->_additionalFees->withCondition($this, 'purchase_fee', null);
            }

        }
        return $this->_additionalFees;
    }


    /**
     * @inheritDoc
     */
    public function getUniqueCode()
    {
        return $this->store_id;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        /** @var $target Product|Order */
        return ($target = $this->getTarget() !== null) ? $target->portal : 'ebay';
    }

    /**
     * @inheritDoc
     */
    public function getTotalOrigin()
    {
        return $this->getAdditionalFees()->getTotalOrigin();
    }

    /**
     * @inheritDoc
     */
    public function getCategory()
    {
        return null;
    }


    private $_user;

    public function getUser()
    {
        if ($this->_user === null && ($this->customer_id !== null && $this->customer_id !== '')) {
            $this->_user = User::findOne($this->customer_id);
        }
        return $this->_user;
    }

    /**
     * @return string
     */
    public function getUserLevel()
    {
        if (($user = $this->getUser()) === null) {
            return User::LEVEL_NORMAL;
        }
        return $user->getUserLevel();
    }

    /**
     * @inheritDoc
     */
    public function getIsNew()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getIsSpecial()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getShippingWeight()
    {
        return $this->shipping_weight;
    }

    /**
     * @inheritDoc
     */
    public function getShippingQuantity()
    {
        return $this->shipping_quantity;
    }

    private $_couriers = [];

    public function getCalculateFee($store, $refresh = false)
    {
        if ((empty($this->_couriers) || $refresh) && !empty($this->getShippingParams())) {

            $location = InternationalShippingCalculator::LOCATION_AMAZON;
            if (($target = $this->getTarget()) instanceof BaseProduct && $target->type === BaseProduct::TYPE_EBAY) {
                $location = InternationalShippingCalculator::LOCATION_EBAY_US;
                $currentSeller = $target->getCurrentProvider();
                if (strtoupper($currentSeller->country_code) !== 'US') {
                    $location = InternationalShippingCalculator::LOCATION_EBAY;
                }
            }
            $calculator = new InternationalShippingCalculator();
            list($ok, $couriers) = $calculator->CalculateFee($this->getShippingParams(), ArrayHelper::getValue($this->getPickUpWareHouse(), 'ref_user_id'), $store->country_code, $store->currency, $location);
            if ($ok && is_array($couriers) && count($couriers) > 0) {
                $this->_couriers = $couriers;
                $firstCourier = $couriers[0];
                $this->getAdditionalFees()->withCondition($this, 'international_shipping_fee', $firstCourier['total_fee']);
                $this->getAdditionalFees()->withCondition($this, 'insurance_fee', $firstCourier['insurance_fee']);
                if ($this->getIsSpecial()) {
                    Yii::info($firstCourier['special_fee'], 'special_fee');
                }
            }

        }
        return $this->_couriers;
    }

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
        return $this->getAdditionalFees()->getStoreManager()->getExchangeRate();
    }

    public function getShippingParams()
    {
        if (($target = $this->getTarget()) === null || ($wh = $this->getPickUpWareHouse()) === null) {
            return [];
        }
        $parcel = [];
        $weight = 0;
        $totalAmount = 0;
        if ($target instanceof Order) {
            $weight = $target->total_weight_temporary * 1000;
            $totalAmount = $target->total_amount_local;
            $items = [];
            foreach ($target->products as $product) {
                $items[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber(($weight / $product->quantity_customer)),
                    'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'quantity' => $product->quantity_customer,
                ];
            }
            $parcel = [
                'weight' => $weight,
                'amount' => $totalAmount,
                'description' => $target->seller ? "order of seller `{$target->seller->seller_name}`" : "",
                'items' => $items
            ];
        } else if ($target instanceof Product) {
            $weight = $target->total_weight_temporary * 1000;
            $totalAmount = $target->total_price_amount_local;

            $sku = [$target->parent_sku];
            if ($target->sku !== null) {
                $sku[] = $target->sku;
            }
            $sku = count($sku) > 1 ? implode('|', $sku) : $sku[0];
            $parcel = [
                'weight' => $weight,
                'amount' => $totalAmount,
                'description' => "product $sku",
                'items' => [
                    [
                        'sku' => $sku,
                        'label_code' => '',
                        'origin_country' => '',
                        'name' => $target->product_name,
                        'desciption' => '',
                        'weight' => WeshopHelper::roundNumber(($weight / $target->quantity_customer)),
                        'amount' => WeshopHelper::roundNumber($target->total_price_amount_local),
                        'quantity' => $target->quantity_customer,
                    ]
                ]
            ];
        } else if ($target instanceof BaseProduct) {
            $weight = $target->getShippingWeight() * 1000;
            $totalAmount = $target->getLocalizeTotalPrice();
            $parcel = [
                'weight' => $weight,
                'amount' => $totalAmount,
                'description' => "{$target->type} {$target->getUniqueCode()}",
                'items' => [
                    [
                        'sku' => $target->getUniqueCode(),
                        'label_code' => '',
                        'origin_country' => '',
                        'name' => $target->item_name,
                        'desciption' => '',
                        'weight' => WeshopHelper::roundNumber(($weight / $target->getShippingQuantity())),
                        'amount' => WeshopHelper::roundNumber($totalAmount),
                        'quantity' => $target->getShippingQuantity(),
                    ]
                ]
            ];
        }
        if (
            ($pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id')) === null ||
            ($userId = ArrayHelper::getValue($wh, 'ref_user_id')) === null ||
            empty($parcel) ||
            $weight === 0 ||
            $totalAmount === 0
        ) {
            return [];
        }
        $store = $this->getAdditionalFees()->storeManager->store;
        $shipTo = ArrayHelper::merge([
            'contact_name' => 'ws calculator',
            'company_name' => '',
            'email' => '',
            'address' => 'ws auto',
            'address2' => '',
            'phone' => '0987654321',
            'phone2' => '',
        ], $this->getDefaultTo($store));
        $params = [
            'config' => [
                'insurance' => $this->accept_insurance,
                'include_special_goods' => $this->getIsSpecial() ? 'Y' : 'N'
            ],
            'ship_from' => [
                'country' => 'US',
                'pickup_id' => $pickUpId
            ],
            'ship_to' => $shipTo,
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
     * @param $store Store
     * @return array
     */
    private function getDefaultTo($store)
    {
        return [
            'province' => $this->province !== null ? $this->province : ($store->country_code === 'ID' ? 3464 : 1),
            'district' => $this->district !== null ? $this->district : ($store->country_code === 'ID' ? 28444 : 8),
            'country' => $store->country_code,
            'zipcode' => $store->country_code === 'ID' ? '14340' : '',
        ];
    }

    public function calculator()
    {
        $store = $this->getAdditionalFees()->getStoreManager()->store;
        $this->getCalculateFee($store, true);

        return [
            'store' => $store->name,
            'target_name' => $this->target_name,
            'target_identity' => $this->target_id,
            'shipping_weight' => $this->shipping_weight,
            'shipping_quantity' => $this->shipping_quantity,
            'exchange' => $this->getExchangeRate(),
            'ship_from' => $this->getPickUpWareHouse(),
            'ship_to' => $this->getDefaultTo($store),
            'additional_fees' => $this->getAdditionalFees()->toArray(),
        ];
    }
}
