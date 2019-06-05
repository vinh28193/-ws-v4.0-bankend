<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%seller}}".
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
        return '{{%seller}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seller_link_store', 'seller_store_description', 'portal'], 'string'],
            [['created_at', 'updated_at', 'seller_remove'], 'integer'],
            [['seller_name', 'seller_store_rate', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'seller_name' => Yii::t('db', 'Seller Name'),
            'seller_link_store' => Yii::t('db', 'Seller Link Store'),
            'seller_store_rate' => Yii::t('db', 'Seller Store Rate'),
            'seller_store_description' => Yii::t('db', 'Seller Store Description'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'seller_remove' => Yii::t('db', 'Seller Remove'),
            'portal' => Yii::t('db', 'Portal'),
            'version' => Yii::t('db', 'Version'),
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
