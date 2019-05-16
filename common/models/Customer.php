<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class Customer
 * @package common\models
 *
 * @property Address $primaryAddress;
 * @property Address $defaultShippingAddress;
 */
class Customer extends \common\models\db\Customer implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active', 'default', 'value' => self::STATUS_ACTIVE],
            ['active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'active' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }



    /**
     * Finds user by username/email/phone
     *
     * @param string $condition
     * @return static|null
     */
    public static function findAdvance($condition)
    {
        return static::find()->where([
            'or',
            ['email' => $condition],
            ['active' => 1]
        ])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'reset_password_token' => $token,
            'active' => self::STATUS_ACTIVE,
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

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
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
        /**
         * Todo : vì cái này liên quan tới việc tự động login
         * cần thiết cho hành động "remember me"
         * cần phải khác biệt với mối user
         * hãy generate và lưu tương ứng với mỗi user
         */

        return "ws{$this->getId()}Customer2019";
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

    public function generateToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
    public function generateAuthClient()
    {
        $this->auth_client = Yii::$app->security->generateRandomString();
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

    public function generateXu() {
        $this->total_xu = 0;
        $this->usable_xu = 0;
        $this->last_use_xu = 0;
        $this->last_revenue_xu = 0;
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
        $this->reset_password_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return null
     */
    public function removePasswordResetToken()
    {
        $this->reset_password_token = null;
    }

    /**
     * Generates new password reset token
     */
    public function getPrimaryAddress(){
        return $this->hasOne(Address::className(),['customer_id' => 'id'])->where([
            'AND',
            ['type' => Address::TYPE_PRIMARY],
            ['is_default' => Address::IS_DEFAULT]
        ]);
    }

    /**
     * @return null
     */
    public function getDefaultShippingAddress(){
        return $this->hasOne(Address::className(),['customer_id' => 'id'])->where([
            'AND',
            ['type' => Address::TYPE_SHIPPING],
            ['is_default' => Address::IS_DEFAULT]
        ]);
    }


}
