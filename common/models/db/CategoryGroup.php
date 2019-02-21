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
 * @property string $created_time
 * @property string $updated_time
 * @property int $active
 * @property int $remove
 */
class CategoryGroup extends \yii\db\ActiveRecord
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
            [['store_id', 'parent_id', 'created_time', 'updated_time', 'active', 'remove'], 'integer'],
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
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'active' => 'Active',
            'remove' => 'Remove',
        ];
    }
}
