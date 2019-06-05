<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%category}}".
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
 */
class Category extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
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
            'id' => Yii::t('db', 'ID'),
            'alias' => Yii::t('db', 'Alias'),
            'site' => Yii::t('db', 'Site'),
            'origin_name' => Yii::t('db', 'Origin Name'),
            'category_group_id' => Yii::t('db', 'Category Group ID'),
            'parent_id' => Yii::t('db', 'Parent ID'),
            'description' => Yii::t('db', 'Description'),
            'weight' => Yii::t('db', 'Weight'),
            'inter_shipping_b' => Yii::t('db', 'Inter Shipping B'),
            'custom_fee' => Yii::t('db', 'Custom Fee'),
            'level' => Yii::t('db', 'Level'),
            'path' => Yii::t('db', 'Path'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'active' => Yii::t('db', 'Active'),
            'remove' => Yii::t('db', 'Remove'),
            'name' => Yii::t('db', 'Name'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
