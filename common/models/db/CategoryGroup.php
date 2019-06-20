<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%category_group}}".
 *
 * @property int $id ID
 * @property string $name
 * @property string $description
 * @property int $store_id
 * @property int $parent_id
 * @property int $is_special
 * @property int $custom_default_value
 * @property int $special_min_amount
 * @property string $rule
 * @property string $rule_description
 * @property string $created_at
 * @property string $updated_at
 * @property int $active
 * @property int $remove
 * @property string $version version 4.0
 */
class CategoryGroup extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'parent_id', 'is_special', 'custom_default_value', 'special_min_amount', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['rule', 'rule_description'], 'string'],
            [['name', 'description', 'version'], 'string', 'max' => 255],
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
            'store_id' => Yii::t('db', 'Store ID'),
            'parent_id' => Yii::t('db', 'Parent ID'),
            'is_special' => Yii::t('db', 'Is Special'),
            'custom_default_value' => Yii::t('db', 'Custom Default Value'),
            'special_min_amount' => Yii::t('db', 'Special Min Amount'),
            'rule' => Yii::t('db', 'Rule'),
            'rule_description' => Yii::t('db', 'Rule Description'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'active' => Yii::t('db', 'Active'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
