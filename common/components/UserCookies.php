<?php


namespace common\components;


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
 * @property $facebook_token
 */

class UserCookies extends Model
{
    const KEY_COOKIES = 'USER_COOKIES';
    public $uuid;
    public $name;
    public $phone;
    public $email;
    public $customer_id;
    public $country_id;
    public $province_id;
    public $district_id;
    public $address;
    public $facebook_id;
    public $facebook_token;
    public function setNewCookies(){
        $value = json_encode($this->toArray());
        Cookies::set(self::KEY_COOKIES,$value);
        return $value;
    }

    public function setCookies(){
        $value_old = json_decode(self::getCookies(),true);
        $value = $this->toArray();
        if($value_old){
            $value = array_merge($value,$value_old);
        }
        Cookies::set(self::KEY_COOKIES,json_encode($value));
        return $value;
    }
    public function setUserCookies(){
        $this->setAttributes(self::getUserCookies());
    }
    public static function getUserCookies($isArray = true){
        return json_decode(self::getCookies(),$isArray);
    }
    public static function getCookies() {
        return Cookies::get(self::KEY_COOKIES);
    }
}