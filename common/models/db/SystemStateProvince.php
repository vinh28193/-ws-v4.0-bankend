<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_state_province".
 *
 * @property int $id ID
 * @property int $country_id id nước
 * @property string $name
 * @property string $name_local
 * @property string $name_alias
 * @property int $display_order
 * @property string $created_time
 * @property string $updated_time
 * @property int $remove
 *
 * @property Address[] $addresses
 * @property Order[] $orders
 * @property Shipment[] $shipments
 * @property SystemDistrict[] $systemDistricts
 * @property SystemCountry $country
 * @property Warehouse[] $warehouses
 */
class SystemStateProvince extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_state_province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'display_order', 'created_time', 'updated_time', 'remove'], 'integer'],
            [['name', 'name_local', 'name_alias'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'name' => 'Name',
            'name_local' => 'Name Local',
            'name_alias' => 'Name Alias',
            'display_order' => 'Display Order',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['province_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['receiver_province_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['receiver_province_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemDistricts()
    {
        return $this->hasMany(SystemDistrict::className(), ['province_id' => 'id']);
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
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['province_id' => 'id']);
    }
}
