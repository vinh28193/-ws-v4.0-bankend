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
 * @property string $version version 4.0
 */
class Address extends \common\components\db\ActiveRecord
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
            [['first_name', 'last_name', 'email', 'phone', 'country_name', 'province_name', 'district_name', 'post_code', 'type', 'version'], 'string', 'max' => 255],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
        ];
    }
}
