<?php

namespace common\models\db_old;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $fullname
 * @property string $avatar
 * @property string $addres
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $currency
 * @property string $joinTime
 * @property string $lastTime
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OrganizationEmployee[] $organizationEmployees
 * @property UserFunction[] $userFunctions
 * @property UserRole[] $userRoles
 */
class User extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_old');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['joinTime', 'lastTime', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['username', 'email', 'fullname', 'auth_key'], 'string', 'max' => 200],
            [['password_hash', 'avatar', 'addres', 'password_reset_token'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'username' => Yii::t('db', 'Username'),
            'password_hash' => Yii::t('db', 'Password Hash'),
            'email' => Yii::t('db', 'Email'),
            'fullname' => Yii::t('db', 'Fullname'),
            'avatar' => Yii::t('db', 'Avatar'),
            'addres' => Yii::t('db', 'Addres'),
            'auth_key' => Yii::t('db', 'Auth Key'),
            'password_reset_token' => Yii::t('db', 'Password Reset Token'),
            'currency' => Yii::t('db', 'Currency'),
            'joinTime' => Yii::t('db', 'Join Time'),
            'lastTime' => Yii::t('db', 'Last Time'),
            'status' => Yii::t('db', 'Status'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationEmployees()
    {
        return $this->hasMany(OrganizationEmployee::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFunctions()
    {
        return $this->hasMany(UserFunction::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['userId' => 'id']);
    }
}
