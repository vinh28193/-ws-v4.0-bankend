<?php


namespace api\modules\v1\models;

use common\additional\AdditionalFeeCollection;
use common\additional\AdditionalFeeInterface;
use common\components\GetUserIdentityTrait;
use common\components\InternationalShippingCalculator;
use common\components\UserCookies;
use common\helpers\WeshopHelper;
use common\models\User;
use common\products\BaseProduct;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AdditionalFeeFrom extends Model implements AdditionalFeeInterface
{
    use GetUserIdentityTrait;

    public $target = 'products';
    public $target_id;
    public $store_id;
    public $customer_id;
    public $item_type = 'calculator';
    public $shipping_weight = 0;
    public $shipping_quantity = 1;

    public $us_amount = 0;

    public $us_tax = 0;

    public $us_ship = 0;


    private $_additionalFees;

    public function getAdditionalFees()
    {
        if ($this->_additionalFees === null) {
            $this->_additionalFees = new AdditionalFeeCollection();
            if ($this->store_id !== null) {
                $this->_additionalFees->getStoreManager()->setStore($this->store_id);
            }
            if ($this->customer_id !== null) {
                $this->setUser($this->customer_id);
            }
            $this->_additionalFees->withConditions($this, [
                'product_price' => $this->us_amount,
                'shipping_fee' => $this->us_ship,
                'tax_fee' => $this->us_tax
            ], false);
            $this->_additionalFees->withCondition($this, 'purchase_fee', null);
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
        return $this->item_type;
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

    /**
     * @return string
     */
    public function getUserLevel()
    {
        if ($this->getUser()) {
            return $this->getUser()->userLevel;
        }
        return User::LEVEL_NORMAL;
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

    /**
     * @return null|array|mixed
     */
    public function getShippingParams()
    {
        if (($wh = $this->getPickUpWareHouse()) === false) {
            return [];
        }
        if (($pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id')) === null) {
            return [];
        }
        $shipTo = [
            'contact_name' => 'ws calculator',
            'company_name' => '',
            'email' => '',
            'address' => 'ws auto',
            'address2' => '',
            'phone' => '0987654321',
            'phone2' => '',
            'province' => 1,
            'district' => 8,
            'country' => $this->getAdditionalFees()->storeManager->store->country_code,
            'zipcode' => '',
        ];
        $userInfoCookie = new UserCookies();
        $userInfoCookie->setUser();
        if ($userInfoCookie && $userInfoCookie->district_id && $userInfoCookie->province_id) {
            $shipTo = ArrayHelper::merge($shipTo, [
                'contact_name' => $userInfoCookie->name ? $userInfoCookie->name : $shipTo['contact_name'],
                'address' => $userInfoCookie->address ? $userInfoCookie->address : $shipTo['address'],
                'phone' => $userInfoCookie->phone ? $userInfoCookie->phone : $shipTo['phone'],
                'province' => $userInfoCookie->province_id ? $userInfoCookie->province_id : $shipTo['province'],
                'district' => $userInfoCookie->district_id ? $userInfoCookie->district_id : $shipTo['district'],
                'zipcode' => $userInfoCookie->zipcode ? $userInfoCookie->zipcode : $shipTo['zipcode']
            ]);
        } else {
            return [];
        }
        $weight = $this->getShippingWeight() * 1000;
        $amount = $this->getAdditionalFees()->getTotalAdditionalFees()[1];
        $params = [
            'ship_from' => [
                'country' => 'US',
                'pickup_id' => $pickUpId
            ],
            'ship_to' => $shipTo,
            'shipments' => [
                'content' => '',
                'total_parcel' => 1,
                'total_amount' => $amount,
                'description' => '',
                'amz_shipment_id' => '',
                'chargeable_weight' => $weight,
                'parcels' => [
                    [
                        'weight' => $weight,
                        'amount' => $amount,
                        'description' => "{$this->item_type} {$this->getUniqueCode()}",
                        'items' => [
                            [
                                'sku' => $this->getUniqueCode(),
                                'label_code' => '',
                                'origin_country' => '',
                                'name' => 'ws item name',
                                'desciption' => '',
                                'weight' => WeshopHelper::roundNumber(($weight / $this->getShippingQuantity())),
                                'amount' => WeshopHelper::roundNumber($amount),
                                'quantity' => $this->getShippingQuantity(),
                            ]
                        ]
                    ]
                ]
            ],
        ];
        return $params;
    }

    private $_couriers = [];

    public function getInternationalShipping($refresh = false)
    {
        if ((empty($this->_couriers) || $refresh) && !empty($this->getShippingParams())) {
            $location = InternationalShippingCalculator::LOCATION_AMAZON;
            $calculator = new InternationalShippingCalculator();
            list($ok, $couriers) = $calculator->CalculateFee($this->getShippingParams(), ArrayHelper::getValue($this->getPickUpWareHouse(), 'ref_user_id'), $this->getStoreManager()->store->country_code, $this->getStoreManager()->store->currency, $location);
            if ($ok && is_array($couriers) && count($couriers) > 0) {
                $this->_couriers = $couriers;
                $firstCourier = $couriers[0];
                $this->getAdditionalFees()->withCondition($this, 'international_shipping_fee', $firstCourier['total_fee']);
            }
        }
        return $this->_couriers;
    }

    /**
     * @return array|mixed|null
     */
    private $_pickUpWareHouse = false;

    public function getPickUpWareHouse()
    {
        if (!$this->_pickUpWareHouse) {
            if (($user = $this->getUser()) !== null && $user->getPickupWarehouse() !== null) {
                $this->_pickUpWareHouse = $user->getPickupWarehouse();
            } elseif (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
                $current = $params['default'];
                $this->_pickUpWareHouse = ArrayHelper::getValue($params, "warehouses.$current", false);
            }
        }
        return $this->_pickUpWareHouse;

    }

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
        return $this->getAdditionalFees()->getStoreManager()->getExchangeRate();
    }
}