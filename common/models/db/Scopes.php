<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "scopes".
 *
 * @property int $id ID
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $level
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property ActionScope[] $actionScopes
 * @property ScopeUser[] $scopeUsers
 */
class Scopes extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scopes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'slug', 'description'], 'string', 'max' => 255],
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
            'slug' => 'Slug',
            'description' => 'Description',
            'level' => 'Level',
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
        return $this->hasMany(ActionScope::className(), ['scope_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScopeUsers()
    {
        return $this->hasMany(ScopeUser::className(), ['scope_id' => 'id']);
    }
}
