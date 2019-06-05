<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%category_custom_policy}}".
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
 * @property string $version version 4.0
 *
 * @property Product[] $products
 */
class CategoryCustomPolicy extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_custom_policy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limit', 'is_special', 'store_id', 'item_maximum_per_category', 'sort_order', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['min_price', 'max_price', 'custom_rate_fee', 'use_percentage', 'custom_fix_fee_per_unit', 'custom_fix_fee_per_weight', 'custom_fix_percent_per_weight', 'weight_maximum_per_category'], 'number'],
            [['name', 'description', 'code', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'name' => Yii::t('db', 'Name'),
            'description' => Yii::t('db', 'Description'),
            'code' => Yii::t('db', 'Code'),
            'limit' => Yii::t('db', 'Limit'),
            'is_special' => Yii::t('db', 'Is Special'),
            'min_price' => Yii::t('db', 'Min Price'),
            'max_price' => Yii::t('db', 'Max Price'),
            'custom_rate_fee' => Yii::t('db', 'Custom Rate Fee'),
            'use_percentage' => Yii::t('db', 'Use Percentage'),
            'custom_fix_fee_per_unit' => Yii::t('db', 'Custom Fix Fee Per Unit'),
            'custom_fix_fee_per_weight' => Yii::t('db', 'Custom Fix Fee Per Weight'),
            'custom_fix_percent_per_weight' => Yii::t('db', 'Custom Fix Percent Per Weight'),
            'store_id' => Yii::t('db', 'Store ID'),
            'item_maximum_per_category' => Yii::t('db', 'Item Maximum Per Category'),
            'weight_maximum_per_category' => Yii::t('db', 'Weight Maximum Per Category'),
            'sort_order' => Yii::t('db', 'Sort Order'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'active' => Yii::t('db', 'Active'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['custom_category_id' => 'id']);
    }
}
