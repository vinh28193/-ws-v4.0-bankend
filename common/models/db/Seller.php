<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property int $id ID
 * @property string $name
 * @property string $link_store
 * @property string $rate
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $portal
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
            [['link_store', 'description', 'portal'], 'string'],
            [['created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'rate'], 'string', 'max' => 255],
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
            'link_store' => 'Link Store',
            'rate' => 'Rate',
            'description' => 'Description',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
            'portal' => 'Portal',
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
