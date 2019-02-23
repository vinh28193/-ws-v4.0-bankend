<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_district".
 *
 * @property int $id ID
 * @property string $name
 * @property string $name_local
 * @property string $name_alias
 * @property int $display_order
 * @property int $province_id
 * @property int $country_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property Address[] $addresses
 * @property Order[] $orders
 * @property Shipment[] $shipments
 * @property SystemCountry $country
 * @property SystemStateProvince $province
 * @property Warehouse[] $warehouses
 */
class SystemDistrict extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['display_order', 'province_id', 'country_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'name_local', 'name_alias'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['province_id' => 'id']],
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
            'name_local' => 'Name Local',
            'name_alias' => 'Name Alias',
            'display_order' => 'Display Order',
            'province_id' => 'Province ID',
            'country_id' => 'Country ID',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['receiver_district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['receiver_district_id' => 'id']);
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
    public function getProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['district_id' => 'id']);
    }
}
