<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "action_scope".
 *
 * @property int $id ID
 * @property int $action_id
 * @property int $scope_id
 * @property string $created_time
 * @property string $updated_time
 * @property int $remove
 *
 * @property Actions $action
 * @property Scopes $scope
 */
class ActionScope extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_scope';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'scope_id', 'created_time', 'updated_time', 'remove'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Actions::className(), 'targetAttribute' => ['action_id' => 'id']],
            [['scope_id'], 'exist', 'skipOnError' => true, 'targetClass' => Scopes::className(), 'targetAttribute' => ['scope_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Action ID',
            'scope_id' => 'Scope ID',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Actions::className(), ['id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScope()
    {
        return $this->hasOne(Scopes::className(), ['id' => 'scope_id']);
    }
}
