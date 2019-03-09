<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "category_group".
 *
 * @property int $id ID
 * @property string $name
 * @property string $description
 * @property int $store_id
 * @property int $parent_id
 * @property string $rule
 * @property string $rule_description
 * @property string $created_at
 * @property string $updated_at
 * @property int $active
 * @property int $remove
 */
class CategoryGroup extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'parent_id', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['rule', 'rule_description'], 'string'],
            [['name', 'description'], 'string', 'max' => 255],
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
            'store_id' => 'Store ID',
            'parent_id' => 'Parent ID',
            'rule' => 'Rule',
            'rule_description' => 'Rule Description',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'active' => 'Active',
            'remove' => 'Remove',
        ];
    }
}
