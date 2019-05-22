<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "category_group".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $store_id
 * @property int $parent_id
 * @property resource $rule
 * @property string $rule_description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
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
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_cms');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'parent_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rule'], 'string'],
            [['name', 'description', 'rule_description'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
