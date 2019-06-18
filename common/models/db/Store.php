<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property int $id ID
 * @property int $country_id
 * @property string $country_code
 * @property string $country_name
 * @property string $name
 * @property string $address
 * @property string $url
 * @property string $locale
 * @property string $currency
 * @property string $symbol
 * @property int $currency_id
 * @property int $status
 * @property int $env PROD or UAT or BETA ...
 * @property string $version version 4.0
 *
 * @property CategoryCustomPolicy[] $categoryCustomPolicies
 * @property Coupon[] $coupons
 * @property Customer[] $customers
 * @property Order[] $orders
 * @property SystemCountry $country
 * @property SystemCurrency $currency0
 * @property Warehouse[] $warehouses
 */
class Store extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'currency_id', 'status', 'env'], 'integer'],
            [['country_code', 'name', 'locale', 'symbol'], 'required'],
            [['address'], 'string'],
            [['country_code', 'locale', 'symbol'], 'string', 'max' => 5],
            [['country_name', 'name', 'url', 'currency', 'version'], 'string', 'max' => 255],
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
            'id' => Yii::t('db', 'ID'),
            'country_id' => Yii::t('db', 'Country ID'),
            'country_code' => Yii::t('db', 'Country Code'),
            'country_name' => Yii::t('db', 'Country Name'),
            'name' => Yii::t('db', 'Name'),
            'address' => Yii::t('db', 'Address'),
            'url' => Yii::t('db', 'Url'),
            'locale' => Yii::t('db', 'Locale'),
            'currency' => Yii::t('db', 'Currency'),
            'symbol' => Yii::t('db', 'Symbol'),
            'currency_id' => Yii::t('db', 'Currency ID'),
            'status' => Yii::t('db', 'Status'),
            'env' => Yii::t('db', 'Env'),
            'version' => Yii::t('db', 'Version'),
        ];
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
