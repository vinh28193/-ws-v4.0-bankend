<?php

namespace frontend\modules\checkout\models;

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
    public $buyer_email;
    public $buyer_name;
    public $buyer_phone;
    public $buyer_address;
    public $buyer_post_code;
    public $buyer_country_id;
    public $buyer_district_id;
    public $buyer_province_id;

    public $note_by_customer;

    public $receiver_address_id;

    public $receiver_email;
    public $receiver_name;
    public $receiver_phone;
    public $receiver_address;
    public $receiver_post_code;
    public $receiver_country_id;
    public $receiver_district_id;
    public $receiver_province_id;

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

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function init()
    {
        parent::init();
        /** @var  $user  Customer */
        if (($user = $this->getUser()) !== null) {
            if (($buyer = $user->primaryAddress) === null) {
                $this->enable_buyer = true;
                $this->save_my_address = true;
            } else {
                $buyerName = implode(' ', [$buyer->first_name, $buyer->last_name]);
                $this->buyer_address = $buyerName;
                $this->buyer_email = $buyer->email;
                $this->buyer_phone = $buyer->phone;
                $this->buyer_address = $buyer->address;
                $this->buyer_post_code = $buyer->post_code;
                $this->buyer_district_id = $buyer->district_id;
                $this->buyer_province_id = $buyer->province_id;
                $this->buyer_country_id = $buyer->country_id;
            }

            if(($receiver = $user->defaultShippingAddress) === null){
                $this->enable_receiver = true;
                $this->save_my_shipping = true;
            }else {
                $buyerName = implode(' ', [$buyer->first_name, $buyer->last_name]);
                $this->receiver_address = $buyerName;
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