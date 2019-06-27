<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property int $id ID
 * @property string $seller_name
 * @property string $seller_link_store
 * @property string $seller_store_rate
 * @property string $seller_store_description
 * @property string $created_at
 * @property string $updated_at
 * @property int $seller_remove
 * @property string $portal
 * @property string $version version 4.0
 * @property string $location
 * @property string $country_code
 *
 * @property Order[] $orders
 * @property Product[] $products
 */
class Seller extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seller';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seller_link_store', 'seller_store_description', 'portal'], 'string'],
            [['created_at', 'updated_at', 'seller_remove'], 'integer'],
            [['seller_name', 'seller_store_rate', 'version', 'location'], 'string', 'max' => 255],
            [['country_code'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seller_name' => 'Seller Name',
            'seller_link_store' => 'Seller Link Store',
            'seller_store_rate' => 'Seller Store Rate',
            'seller_store_description' => 'Seller Store Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'seller_remove' => 'Seller Remove',
            'portal' => 'Portal',
            'version' => 'Version',
            'location' => 'Location',
            'country_code' => 'Country Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['seller_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['seller_id' => 'id']);
    }
}
