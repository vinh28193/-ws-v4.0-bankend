<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%system_state_province}}".
 *
 * @property int $id ID
 * @property int $country_id id nước
 * @property string $name
 * @property string $name_local
 * @property string $name_alias
 * @property int $display_order
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $version version 4.0
 *
 * @property Shipment[] $shipments
 * @property SystemDistrict[] $systemDistricts
 * @property SystemCountry $country
 * @property Warehouse[] $warehouses
 */
class SystemStateProvince extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%system_state_province}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'display_order', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'name_local', 'name_alias', 'version'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'name' => Yii::t('db', 'Name'),
            'name_local' => Yii::t('db', 'Name Local'),
            'name_alias' => Yii::t('db', 'Name Alias'),
            'display_order' => Yii::t('db', 'Display Order'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
        ];
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
