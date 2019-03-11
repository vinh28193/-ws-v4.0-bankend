<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $district_id
 * @property int $province_id
 * @property int $country_id
 * @property int $store_id
 * @property string $address
 * @property int $type 1: Full Operation Warehouse , 2 : Transit Warehouse 
 * @property string $warehouse_group 1: Nhóm Transit , 2 : nhóm lưu trữ, 3 : nhóm note purchase. Các nhóm sẽ đc khai báo const 
 * @property string $post_code
 * @property string $telephone
 * @property string $email
 * @property string $contact_person
 * @property int $ref_warehouse_id
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 *
 * @property Package[] $packages
 * @property Shipment[] $shipments
 * @property ShipmentReturned[] $shipmentReturneds
 * @property SystemCountry $country
 * @property SystemDistrict $district
 * @property SystemStateProvince $province
 * @property Store $store
 */
class Warehouse extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['district_id', 'province_id', 'country_id', 'store_id', 'type', 'ref_warehouse_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'address', 'warehouse_group', 'post_code', 'telephone', 'email', 'contact_person'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['province_id' => 'id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
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
            'description' => 'Description',
            'district_id' => 'District ID',
            'province_id' => 'Province ID',
            'country_id' => 'Country ID',
            'store_id' => 'Store ID',
            'address' => 'Address',
            'type' => 'Type',
            'warehouse_group' => 'Warehouse Group',
            'post_code' => 'Post Code',
            'telephone' => 'Telephone',
            'email' => 'Email',
            'contact_person' => 'Contact Person',
            'ref_warehouse_id' => 'Ref Warehouse ID',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['warehouse_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['warehouse_send_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipmentReturneds()
    {
        return $this->hasMany(ShipmentReturned::className(), ['warehouse_send_id' => 'id']);
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
    public function getDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'district_id']);
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
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
