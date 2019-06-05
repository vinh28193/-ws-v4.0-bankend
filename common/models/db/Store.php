<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%store}}".
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
 * @property string $version version 4.0
 *
 * @property Customer[] $customers
 * @property Order[] $orders
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
            [['address'], 'string'],
            [['locale', 'name', 'country_name', 'url', 'currency', 'version'], 'string', 'max' => 255],
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
            'locale' => Yii::t('db', 'Locale'),
            'name' => Yii::t('db', 'Name'),
            'country_name' => Yii::t('db', 'Country Name'),
            'address' => Yii::t('db', 'Address'),
            'url' => Yii::t('db', 'Url'),
            'currency' => Yii::t('db', 'Currency'),
            'currency_id' => Yii::t('db', 'Currency ID'),
            'status' => Yii::t('db', 'Status'),
            'env' => Yii::t('db', 'Env'),
            'version' => Yii::t('db', 'Version'),
        ];
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
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['store_id' => 'id']);
    }
}
