<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%authorization_codes}}".
 *
 * @property int $id
 * @property string $code
 * @property int $expires_at
 * @property int $user_id
 * @property string $type
 * @property string $app_id
 * @property int $created_at
 * @property int $updated_at
 */
class AuthorizationCodes extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%authorization_codes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'expires_at', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['expires_at', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 150],
            [['type'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'code' => Yii::t('db', 'Code'),
            'expires_at' => Yii::t('db', 'Expires At'),
            'user_id' => Yii::t('db', 'User ID'),
            'type' => Yii::t('db', 'Type'),
            'app_id' => Yii::t('db', 'App ID'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
        ];
    }
}
