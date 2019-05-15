<?php

namespace frontend\modules\checkout\models;

use common\models\Address;
use common\models\Customer;
use Yii;
use yii\base\Model;

/**
 * Class ShippingForm
 * @package frontend\modules\checkout\models
 *
 * @property Customer|null $user
 */
class ShippingForm extends Model
{

    public $customer_id;

    public $buyer_name;
    public $buyer_email;
    public $buyer_phone;
    public $buyer_address;
    public $buyer_post_code;
    public $buyer_country_id;
    public $buyer_province_id;
    public $buyer_district_id;

    public $note_by_customer;

    public $receiver_address_id;

    public $receiver_name;
    public $receiver_email;
    public $receiver_phone;
    public $receiver_address;
    public $receiver_post_code;
    public $receiver_country_id;
    public $receiver_province_id;
    public $receiver_district_id;

    public $enable_buyer = false;
    public $save_my_address = false;
    public $enable_receiver = false;
    public $save_my_shipping = false;

    /**
     * @var Customer|null
     */
    private $_user;

    /**
     * @return Customer|null
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    public function ensureReceiver()
    {
        if ($this->enable_buyer === true || $this->receiver_address_id === null) {
            $name = explode(' ',$this->receiver_name);
            $firstName = array_pop($name);
            $lastName = implode(' ',$name);
            $address = new Address([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->buyer_email,
                'phone' => $this->buyer_phone,
                'address' => $this->buyer_address,
                'post_code' => $this->buyer_post_code,
                'district_id' => $this->buyer_district_id,
                'province_id' => $this->buyer_province_id,
                'country_id' => $this->buyer_country_id,
                'type' => Address::TYPE_SHIPPING,
                'is_default' => 0,
                'remove' => 0,
            ]);
            $address->save(false);
            $this->receiver_address_id = $address->id;
        }
    }

    public function setDefaultValues()
    {
        /** @var  $user  Customer */
        if (($user = $this->getUser()) !== null) {
            if (($buyer = $user->primaryAddress) === null) {
                $this->enable_buyer = true;
                $this->save_my_address = true;
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
                $this->enable_receiver = true;
                $this->save_my_shipping = true;
            } else {
                $this->receiver_address_id = $receiver->id;
                $buyerName = implode(' ', [$buyer->first_name, $buyer->last_name]);
                $this->receiver_name = $buyerName;
                $this->receiver_email = $buyer->email;
                $this->receiver_phone = $buyer->phone;
                $this->receiver_address = $buyer->address;
                $this->receiver_post_code = $buyer->post_code;
                $this->receiver_district_id = $buyer->district_id;
                $this->receiver_province_id = $buyer->province_id;
                $this->receiver_country_id = $buyer->country_id;
            }
        }
    }
}