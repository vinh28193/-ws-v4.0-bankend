<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id ID
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property int $country_id
 * @property string $country_name
 * @property int $province_id
 * @property string $province_name
 * @property int $district_id
 * @property string $district_name
 * @property string $address
 * @property string $post_code
 * @property int $store_id
 * @property string $type
 * @property int $is_default
 * @property int $customer_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property SystemCountry $country
 * @property Customer $customer
 * @property SystemDistrict $district
 * @property SystemStateProvince $province
 * @property Store $store
 * @property Order[] $orders
 * @property Shipment[] $shipments
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'province_id', 'district_id', 'store_id', 'is_default', 'customer_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['address'], 'string'],
            [['first_name', 'last_name', 'email', 'phone', 'country_name', 'province_name', 'district_name', 'post_code', 'type'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'country_id' => 'Country ID',
            'country_name' => 'Country Name',
            'province_id' => 'Province ID',
            'province_name' => 'Province Name',
            'district_id' => 'District ID',
            'district_name' => 'District Name',
            'address' => 'Address',
            'post_code' => 'Post Code',
            'store_id' => 'Store ID',
            'type' => 'Type',
            'is_default' => 'Is Default',
            'customer_id' => 'Customer ID',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
        ];
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
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['receiver_address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['receiver_address_id' => 'id']);
    }
}
