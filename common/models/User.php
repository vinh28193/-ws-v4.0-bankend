<?php

namespace common\models;


use Yii;
use yii\web\IdentityInterface;
use common\components\UserApiGlobalIdentityInterface;
use common\components\UserPublicIdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends \common\models\db\User implements IdentityInterface, UserApiGlobalIdentityInterface, UserPublicIdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    /*
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    */

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $access_token = AccessTokens::findOne(['token' => $token]);
        if ($access_token) {
            if ($access_token->expires_at < time()) {
                \Yii::$app->response->setStatusCode(200);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = ['success' => false, 'message' => "Access token expired"];
                return false;
            }

            return static::findOne(['id' => $access_token->user_id]);
        } else {
            return (false);
        }
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by phone
     *
     * @param string $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        // ToDo Check time bi nho hon

//        if (!static::isPasswordResetTokenValid($token)) {
//            return null;
//        }

        Yii::info('success', 'findByPasswordResetToken.');
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = isset(Yii::$app->params['user.passwordResetTokenExpire']) ? isset(Yii::$app->params['user.passwordResetTokenExpire']) : 3600;
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getPublicIdentity()
    {
        $authManager = Yii::$app->authManager;
        $permissions = $authManager->getPermissionsByUser($this->getId());
        $role = $authManager->getRolesByUser($this->getId());
        return [
            'id' => $this->getId(),
            'username' => $this->username,
            'email' => $this->email,
            'role' => !empty($role) ? array_keys($role) : [],
            'permission' => !empty($permissions) ? array_keys($permissions) : []
        ];
    }

    public static function findAdvance($condition)
    {
        return static::find()->where([
            'and',
            ['email' => $condition],
            ['status' => 1]
        ])->one();
    }


    /**
     * Generates new password reset token
     */
    public function getPrimaryAddress()
    {
        return $this->hasOne(Address::className(), ['customer_id' => 'id'])->where([
            'AND',
            ['type' => Address::TYPE_PRIMARY],
            ['is_default' => Address::IS_DEFAULT]
        ]);
    }

    /**
     * @return null
     */
    public function getDefaultShippingAddress()
    {
        return $this->hasOne(Address::className(), ['customer_id' => 'id'])->where([
            'AND',
            ['type' => Address::TYPE_SHIPPING],
            ['is_default' => Address::IS_DEFAULT]
        ]);
    }

}
