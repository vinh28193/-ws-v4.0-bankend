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
        return static::findOne([
            'AND',
            [
                'OR',
                 ['email' => $condition],
                 ['phone' => $condition],
                 ['username' => $condition]
            ],
            ['active' => self::STATUS_ACTIVE]
        ]);
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
            'password_reset_token' => $token,
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
     * @return null
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
