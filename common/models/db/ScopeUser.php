<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "scope_user".
 *
 * @property int $id ID
 * @property int $user_id
 * @property int $scope_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property Scopes $scope
 * @property User $user
 */
class ScopeUser extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scope_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'scope_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['scope_id'], 'exist', 'skipOnError' => true, 'targetClass' => Scopes::className(), 'targetAttribute' => ['scope_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'scope_id' => 'Scope ID',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScope()
    {
        return $this->hasOne(Scopes::className(), ['id' => 'scope_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
