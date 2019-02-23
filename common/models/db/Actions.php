<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "actions".
 *
 * @property int $id ID
 * @property string $name
 * @property string $action
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property ActionScope[] $actionScopes
 */
class Actions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'action', 'description'], 'string', 'max' => 255],
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
            'action' => 'Action',
            'description' => 'Description',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionScopes()
    {
        return $this->hasMany(ActionScope::className(), ['action_id' => 'id']);
    }
}
