<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "category_custom_policy".
 *
 * @property int $id ID
 * @property string $name
 * @property string $description
 * @property string $code
 * @property int $limit
 * @property int $is_special
 * @property string $min_price
 * @property string $max_price
 * @property string $custom_rate_fee
 * @property string $use_percentage
 * @property string $custom_fix_fee_per_unit
 * @property string $custom_fix_fee_per_weight
 * @property string $custom_fix_percent_per_weight
 * @property int $store_id
 * @property int $item_maximum_per_category
 * @property string $weight_maximum_per_category
 * @property int $sort_order
 * @property string $created_at
 * @property string $updated_at
 * @property int $active
 * @property int $remove
 *
 * @property Store $store
 * @property Product[] $products
 */
class CategoryCustomPolicy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_custom_policy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limit', 'is_special', 'store_id', 'item_maximum_per_category', 'sort_order', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['min_price', 'max_price', 'custom_rate_fee', 'use_percentage', 'custom_fix_fee_per_unit', 'custom_fix_fee_per_weight', 'custom_fix_percent_per_weight', 'weight_maximum_per_category'], 'number'],
            [['name', 'description', 'code'], 'string', 'max' => 255],
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
            'code' => 'Code',
            'limit' => 'Limit',
            'is_special' => 'Is Special',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'custom_rate_fee' => 'Custom Rate Fee',
            'use_percentage' => 'Use Percentage',
            'custom_fix_fee_per_unit' => 'Custom Fix Fee Per Unit',
            'custom_fix_fee_per_weight' => 'Custom Fix Fee Per Weight',
            'custom_fix_percent_per_weight' => 'Custom Fix Percent Per Weight',
            'store_id' => 'Store ID',
            'item_maximum_per_category' => 'Item Maximum Per Category',
            'weight_maximum_per_category' => 'Weight Maximum Per Category',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'active' => 'Active',
            'remove' => 'Remove',
        ];
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['custom_category_id' => 'id']);
    }
}
