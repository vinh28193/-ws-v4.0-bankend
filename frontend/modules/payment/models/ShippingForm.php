<?php

namespace frontend\modules\payment\models;

use common\components\StoreManager;
use common\models\SystemDistrict;
use common\models\SystemStateProvince;
use Yii;
use yii\base\Model;
use common\models\Address;
use common\models\Customer;
use common\components\GetUserIdentityTrait;
use yii\db\Query;

/**
 * Class ShippingForm
 * @package frontend\modules\payment\models
 *
 * @property Customer|null $user
 */
class ShippingForm extends Model
{

    use GetUserIdentityTrait;

    const OTHER_RECEIVER_YES = 1;
    const OTHER_RECEIVER_NO = 0;

    public $cartIds;

    public $checkoutType;

    public $customer_id;

    public $buyer_name;
    public $buyer_email;
    public $buyer_phone;
    public $buyer_address;
    public $buyer_post_code;
    public $buyer_country_id;
    public $buyer_province_id;
    public $buyer_district_id;

    public $receiver_name;
    public $receiver_email;
    public $receiver_phone;
    public $receiver_address;
    public $receiver_post_code;
    public $receiver_country_id;
    public $receiver_province_id;
    public $receiver_district_id;

    public $other_receiver = self::OTHER_RECEIVER_NO;
    public $note_by_customer;

    public function attributes()
    {
        return [
            'customer_id', 'cartIds', 'checkoutType',
            'buyer_name', 'buyer_email', 'buyer_phone', 'buyer_address', 'buyer_province_id', 'buyer_district_id',
            'receiver_name', 'receiver_email', 'receiver_phone', 'receiver_address', 'receiver_post_code', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id',
            'other_receiver', 'note_by_customer',
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'receiver_name', 'receiver_email', 'receiver_phone', 'receiver_address', 'receiver_province_id', 'receiver_district_id',
                ],
                'required', 'when' => function ($model) {
                return (int)$model->other_receiver === self::OTHER_RECEIVER_YES;
            }
            ],
            [
                [
                    'buyer_name', 'buyer_email', 'buyer_phone', 'buyer_address', 'buyer_province_id', 'buyer_district_id',
                ],
                'required'
            ],
            [
                [
                    'buyer_email', 'receiver_email'
                ],
                'email'
            ],
            [
                [
                    'receiver_phone', 'receiver_phone'
                ],
                '\common\validators\PhoneValidator'
            ],
            [
                [
                    'buyer_address', 'buyer_address',
                    'buyer_email', 'receiver_email',
                    'receiver_phone', 'receiver_phone',
                    'note_by_customer'
                ],
                'string', 'max' => 255
            ],
            [
                [
                    'buyer_address', 'buyer_address',
                    'buyer_email', 'receiver_email',
                    'receiver_phone', 'receiver_phone',
                    'note_by_customer'
                ],
                'filter', 'filter' => 'trim'
            ],
            [
                [
                    'buyer_province_id', 'buyer_district_id',
                    'receiver_province_id', 'receiver_district_id',
                ],
                'validateSelectedValue'
            ],
            [
                [
                    'cartIds', 'checkoutType',
                    'buyer_post_code', 'buyer_country_id',
                    'receiver_post_code', 'receiver_country_id',
                    'note_by_customer', 'other_receiver',
                    'customer_id', 'receiver_district_id',
                ],
                'safe'
            ],
            [
                [
                    'buyer_country_id', 'receiver_country_id',
                    'buyer_province_id', 'buyer_district_id',
                    'receiver_province_id', 'receiver_district_id',
                    'other_receiver',
                    'customer_id',
                ],
                'filter', 'filter' => function ($v) {
                return (int)$v;
            }
            ]

        ];
    }

    public function validateSelectedValue($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->$attribute === 0 || $this->$attribute === '') {
                $this->addError($attribute, " {$attribute} must be select");
            }
        }
    }


    public function getAttributeLabels()
    {
        return [
            'customer_id' => 'customer_id',
            'buyer_name' => 'buyer_name',
            'buyer_email' => 'buyer_email',
            'buyer_phone' => 'buyer_phone',
            'buyer_address' => 'buyer_address',
            'buyer_post_code' => 'buyer_post_code',
            'buyer_country_id' => 'buyer_country_id',
            'buyer_province_id' => 'buyer_province_id',
            'buyer_district_id' => 'buyer_district_id',
            'receiver_name' => 'receiver_name',
            'receiver_email' => 'receiver_email',
            'receiver_phone' => 'receiver_phone',
            'receiver_address' => 'receiver_address',
            'receiver_post_code' => 'receiver_post_code',
            'receiver_country_id' => 'receiver_country_id',
            'receiver_province_id' => 'receiver_province_id',
            'receiver_district_id' => 'receiver_district_id',
            'other_receiver' => 'other_receiver',
            'note_by_customer' => 'note_by_customer',
        ];
    }

    public function setDefaultValues()
    {
        /** @var  $store  StoreManager */
        $store = Yii::$app->storeManager;
        $this->buyer_country_id = $store->store->country_id;
        /** @var  $user  Customer */
        if (($user = $this->getUser()) !== null) {
            $this->customer_id = $user->id;
            if (($buyer = $user->primaryAddress) === null) {
                $buyerName = implode(' ', [$user->first_name, $user->last_name]);
                $this->buyer_name = $buyerName;
                $this->buyer_email = $user->email;
                $this->buyer_phone = $user->phone;
                $this->buyer_address = '';
                $this->buyer_post_code = '';
                $this->buyer_district_id = 0;
                $this->buyer_province_id = 0;
                $this->buyer_country_id = 0;
            } else {
                $buyerName = implode(' ', [$buyer->first_name, $buyer->last_name]);
                $this->buyer_name = $buyerName;
                $this->buyer_email = $buyer->email;
                $this->buyer_phone = $buyer->phone;
                $this->buyer_address = $buyer->address;
                $this->buyer_post_code = $buyer->post_code;
                $this->buyer_district_id = $buyer->district_id;
                $this->buyer_province_id = $buyer->province_id;
                $this->buyer_country_id = $buyer->country_id;
            }

            if (($receiver = $user->defaultShippingAddress) === null) {
                $this->other_receiver = self::OTHER_RECEIVER_YES;
                $receiverName = implode(' ', [$user->first_name, $user->last_name]);
                $this->receiver_name = $receiverName;
                $this->receiver_email = $user->email;
                $this->receiver_phone = $user->phone;
                $this->receiver_address = '';
                $this->receiver_post_code = '';
                $this->receiver_district_id = 0;
                $this->receiver_province_id = 0;
                $this->receiver_country_id = 0;
            } else {
                $receiverName = implode(' ', [$receiver->first_name, $receiver->last_name]);
                $this->receiver_name = $receiverName;
                $this->receiver_email = $receiver->email;
                $this->receiver_phone = $receiver->phone;
                $this->receiver_address = $receiver->address;
                $this->receiver_post_code = $receiver->post_code;
                $this->receiver_district_id = $receiver->district_id;
                $this->receiver_province_id = $receiver->province_id;
                $this->receiver_country_id = $receiver->country_id;
            }
            $this->customer_id = $user->id;
        } else {
            $this->other_receiver = self::OTHER_RECEIVER_YES;
        }
    }

    public function ensureReceiver()
    {
        if ((int)$this->other_receiver === self::OTHER_RECEIVER_NO) {
            $this->receiver_name = $this->buyer_name;
            $this->receiver_email = $this->buyer_email;
            $this->receiver_phone = $this->buyer_phone;
            $this->receiver_address = $this->buyer_address;
            $this->receiver_post_code = $this->buyer_post_code;
            $this->receiver_district_id = $this->buyer_district_id;
            $this->setReceiverDistrictName($this->getBuyerDistrictName());
            $this->receiver_province_id = $this->buyer_province_id;
            $this->setBuyerProvinceName($this->getBuyerDistrictName());
            $this->receiver_country_id = $this->buyer_country_id;
        }
    }

    private $_buyerDistrictName;

    public function getBuyerDistrictName()
    {
        if ($this->_buyerDistrictName === null && $this->buyer_district_id != 0) {
            $this->_buyerDistrictName = $this->createQuery('name', SystemDistrict::className(), ['id' => $this->buyer_district_id]);
        }
        return $this->_buyerDistrictName;
    }

    public function setBuyerDistrictName($name)
    {
        $this->_buyerDistrictName = $name;
    }

    private $_receiverDistrictName;

    public function getReceiverDistrictName()
    {
        if ($this->_receiverDistrictName === null && $this->receiver_district_id != 0) {
            $this->_receiverDistrictName = $this->createQuery('name', SystemDistrict::className(), ['id' => $this->receiver_district_id]);
        }
        return $this->_receiverDistrictName;
    }

    public function setReceiverDistrictName($name)
    {
        $this->_receiverDistrictName = $name;
    }


    private $_buyerProvinceName;

    public function getBuyerProvinceName()
    {
        if ($this->_buyerProvinceName === null && $this->buyer_province_id != 0) {
            $this->_buyerProvinceName = $this->createQuery('name', SystemStateProvince::className(), ['id' => $this->buyer_province_id]);
        }
        return $this->_buyerProvinceName;
    }

    public function setBuyerProvinceName($name)
    {
        $this->_buyerProvinceName = $name;
    }

    private $_receiverProvinceName;

    public function getReceiverProvinceName()
    {
        if ($this->_receiverProvinceName === null && $this->receiver_province_id != 0) {
            $this->_receiverProvinceName = $this->createQuery('name', SystemStateProvince::className(), ['id' => $this->receiver_province_id]);
        }
        return $this->_receiverProvinceName;
    }

    public function setReceiverProvinceName($name)
    {
        $this->_receiverProvinceName = $name;
    }


    private function createQuery($selectColumn, $refClass, $condition)
    {
        /** @var  $class yii\db\ActiveRecord */
        $class = $refClass;
        $q = new Query();
        $q->from(['q' => $class::tableName()]);
        $q->select("[[$selectColumn]]");
        $q->where($condition);
        return $q->column($class::getDb())[0];
    }


}