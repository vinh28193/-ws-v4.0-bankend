<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%address}}".
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
 * @property string $version version 4.0
 *
 * @property Shipment[] $shipments
 */
class Address extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'province_id', 'district_id', 'store_id', 'is_default', 'customer_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['address'], 'string'],
            [['first_name', 'last_name', 'email', 'phone', 'country_name', 'province_name', 'district_name', 'post_code', 'type', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'first_name' => Yii::t('db', 'First Name'),
            'last_name' => Yii::t('db', 'Last Name'),
            'email' => Yii::t('db', 'Email'),
            'phone' => Yii::t('db', 'Phone'),
            'country_id' => Yii::t('db', 'Country ID'),
            'country_name' => Yii::t('db', 'Country Name'),
            'province_id' => Yii::t('db', 'Province ID'),
            'province_name' => Yii::t('db', 'Province Name'),
            'district_id' => Yii::t('db', 'District ID'),
            'district_name' => Yii::t('db', 'District Name'),
            'address' => Yii::t('db', 'Address'),
            'post_code' => Yii::t('db', 'Post Code'),
            'store_id' => Yii::t('db', 'Store ID'),
            'type' => Yii::t('db', 'Type'),
            'is_default' => Yii::t('db', 'Is Default'),
            'customer_id' => Yii::t('db', 'Customer ID'),
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
        return $this->hasMany(Shipment::className(), ['receiver_address_id' => 'id']);
    }
}
