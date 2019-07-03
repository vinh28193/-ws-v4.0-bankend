<?php


namespace common\components;


use common\models\SystemDistrict;
use common\models\SystemStateProvince;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Class UserCookies
 * @package common\components
 * @property $uuid
 * @property $name
 * @property $phone
 * @property $email
 * @property $customer_id
 * @property $country_id
 * @property $province_id
 * @property $district_id
 * @property $address
 * @property $facebook_id
 * @property $zipcode
 * @property $facebook_token
 */

class UserCookies extends Model
{
    const KEY_COOKIES = '_USER_COOKIES';
    public $uuid;
    public $name;
    public $phone;
    public $email;
    public $customer_id;
    public $country_id;
    public $province_id;
    public $district_id;
    public $address;
    public $zipcode;
    public $facebook_id;
    public $facebook_token;
    public function setNewCookies(){
        $value = json_encode($this->toArray());
        Cookies::set(self::KEY_COOKIES,$value);
        return $value;
    }

    public function setCookies(){
        $value = json_encode($this->toArray());
        Cookies::set(self::KEY_COOKIES,$value);
        return $value;
    }
    public function setUser(){
        $this->setAttributes(self::getUserCookies(),false);
        if(!Yii::$app->user->isGuest){
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $this->email = $user->email;
            $this->name = implode(' ',[$user->first_name,$user->last_name]);
            $this->phone = $user->phone;
            $this->customer_id = $user->id;
            $addressDefault = $user->defaultShippingAddress ? $user->defaultShippingAddress : $user->defaultPrimaryAddress;
            if($addressDefault){
                $this->district_id = $addressDefault->district_id;
                $this->province_id = $addressDefault->province_id;
                $this->country_id = $addressDefault->country_id;
                $this->address = $addressDefault->address;
                $this->zipcode = $addressDefault->post_code;
            }
            $this->setCookies();
        }
        return $this;
    }
    public static function getUserCookies($isArray = true){
        return json_decode(self::getCookies(),$isArray);
    }
    public static function getCookies() {
        return Cookies::get(self::KEY_COOKIES);
    }

    /**
     * @return SystemDistrict|null
     */
    public function getDistrict(){
        $key = "DISTRICT_".$this->district_id;
        $district = Yii::$app->cache->get($key);
        if(!$district){
            $district = SystemDistrict::findOne($this->district_id);
            Yii::$app->cache->set($key,$district,60*60*24);
        }
        return $district;
    }

    /**
     * @return SystemStateProvince|null
     */
    public function getProvince(){
        $key = "PROVINCE_".$this->province_id;
        $province = Yii::$app->cache->get($key);
        if(!$province){
            $province = SystemStateProvince::findOne($this->province_id);
            Yii::$app->cache->set($key,$province,60*60*24);
        }
        return $province;
    }
    public function checkAddress(){
        return $this->district_id && $this->province_id;
    }
}