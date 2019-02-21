<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id ID
 * @property int $country_id
 * @property string $locale
 * @property string $name
 * @property string $country_name
 * @property string $address
 * @property string $url
 * @property string $currency
 * @property int $currency_id
 * @property int $status
 * @property int $env PROD or UAT or BETA ...
 *
 * @property Address[] $addresses
 * @property CategoryCustomPolicy[] $categoryCustomPolicies
 * @property Coupon[] $coupons
 * @property Customer[] $customers
 * @property Order[] $orders
 * @property SystemCountry $country
 * @property SystemCurrency $currency0
 * @property Warehouse[] $warehouses
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'currency_id', 'status', 'env'], 'integer'],
            [['address'], 'string'],
            [['locale', 'name', 'country_name', 'url', 'currency'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCurrency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'locale' => 'Locale',
            'name' => 'Name',
            'country_name' => 'Country Name',
            'address' => 'Address',
            'url' => 'Url',
            'currency' => 'Currency',
            'currency_id' => 'Currency ID',
            'status' => 'Status',
            'env' => 'Env',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryCustomPolicies()
    {
        return $this->hasMany(CategoryCustomPolicy::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency0()
    {
        return $this->hasOne(SystemCurrency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['store_id' => 'id']);
    }
}
