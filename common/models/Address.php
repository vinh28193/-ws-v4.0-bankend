<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 25/02/2019
 * Time: 16:48
 */

namespace common\models;


use common\components\Cache;

class Address extends \common\models\db\Address
{
    const TYPE_PRIMARY = 'primary';
    const TYPE_SHIPPING = 'shipping';

    const IS_DEFAULT = 1;
    public static function FindbyCustomerId($customerId = null, $cache = false)
    {
        $key = 'CACHE_ADDRESS_BY_CUSTOMER_ID_' . $customerId;
        $data = Cache::get($key);
        if (!$data || $customerId == null || $cache == false) {
            $data = self::find()->where(['customer_id' => $customerId])->orderBy(['id' => SORT_DESC])->asArray()->all();
            Cache::set($key, $data, 60 * 60);
        }
        return $data;
    }
    public static function creatAdress($address, $id_customer = null)
    {
        if (isset($address['receiverAddressId']) && $address['receiverAddressId'] > 0) {
            $model = self::findOne($address['receiverAddressId']);
        } else {
            $model = new self();
        }
        $model->first_name = $address['receiverName'];
        $model->email = $address['receiverEmail'];
        $model->country_id = $address['receiverCountryId'];
        $model->province_id = $address['receiverCityId'];
        $model->district_id = $address['receiverDistrictId'];
        $model->address = $address['receiverAddress'];
        $model->phone = $address['receiverPhone'];
        $model->customer_id = $id_customer;
        $model->save(false);
    }

    public static function getById($id, $cache = true)
    {
        $key = 'CACHE_ADDRESS_BY_ID_' . $id;
        $data = Cache::get($key);
        if (!$data || $id == null || $cache == false) {
            $data = Address::find()->where(['id' => $id])->one();
            Cache::set($key, $data, 60 * 60 * 24);
        }
        return $data;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $keys = [
            'CACHE_ADDRESS_BY_CUSTOMER_ID_' . $this->customer_id,
            'CACHE_ADDRESS_BY_ID_' . $this->id
        ];
        foreach ($keys as $key=>$value){
            Cache::delete($value);
        }
    }

}