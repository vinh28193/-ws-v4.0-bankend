<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_country".
 *
 * @property int $id ID
 * @property string $name
 * @property string $country_code
 * @property string $country_code_2
 * @property string $language Nếu có nhiều , viết cách nhau bằng dấu phẩy
 * @property string $status
 * @property string $version version 4.0
 *
 * @property Address[] $addresses
 * @property Order[] $orders
 * @property Shipment[] $shipments
 * @property Store[] $stores
 * @property SystemDistrict[] $systemDistricts
 * @property SystemStateProvince[] $systemStateProvinces
 * @property Warehouse[] $warehouses
 */
class SystemCountry extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_code', 'country_code_2', 'language', 'status', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country_code' => 'Country Code',
            'country_code_2' => 'Country Code 2',
            'language' => 'Language',
            'status' => 'Status',
            'version' => 'Version',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['receiver_country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['receiver_country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Store::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemDistricts()
    {
        return $this->hasMany(SystemDistrict::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemStateProvinces()
    {
        return $this->hasMany(SystemStateProvince::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['country_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\SystemStateProvinceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\SystemStateProvinceQuery(get_called_class());
    }
}
