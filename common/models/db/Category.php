<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id ID
 * @property string $alias
 * @property string $site ebay / amazon / amazon-jp
 * @property string $origin_name
 * @property int $category_group_id
 * @property string $parent_id
 * @property string $description
 * @property double $weight
 * @property double $inter_shipping_b
 * @property string $custom_fee
 * @property int $level
 * @property string $path
 * @property string $created_at
 * @property string $updated_at
 * @property int $active
 * @property int $remove
 * @property string $name
 * @property string $version version 4.0
 *
 * @property Product[] $products
 */
class Category extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_group_id', 'level', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['weight', 'inter_shipping_b', 'custom_fee'], 'number'],
            [['alias', 'site', 'origin_name', 'parent_id', 'description', 'path', 'version'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'site' => 'Site',
            'origin_name' => 'Origin Name',
            'category_group_id' => 'Category Group ID',
            'parent_id' => 'Parent ID',
            'description' => 'Description',
            'weight' => 'Weight',
            'inter_shipping_b' => 'Inter Shipping B',
            'custom_fee' => 'Custom Fee',
            'level' => 'Level',
            'path' => 'Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'active' => 'Active',
            'remove' => 'Remove',
            'name' => 'Name',
            'version' => 'Version',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CategoryQuery(get_called_class());
    }
}
