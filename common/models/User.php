<?php

namespace common\models;


use common\components\UserCookies;
use common\models\db\AuthAssignment;
use Yii;
use yii\web\IdentityInterface;
use common\models\db\User as DbUser;
use common\components\UserApiGlobalIdentityInterface;
use common\components\UserPublicIdentityInterface;

/**
 * Class User
 * @package common\models
 * @property-read Address $defaultPrimaryAddress
 * @property-read Address $defaultShippingAddress
 * @property-read string $userLevel
 * @property Address[] $shippingAddress
 * @property Address[] $primaryAddress
 * @property AuthAssignment[] $scopeAuth
 */
class User extends DbUser implements IdentityInterface, UserApiGlobalIdentityInterface, UserPublicIdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const EMPLOYEE_HCM = 2;
    const EMPLOYEE_HN = 1;
    const EMPLOYEE = 1;
    const CUSTOMER = 0;
    const RETAIL_CUSTOMER = 1;
    const WHOLESALE_CUSTOMER = 2;

    const LEVEL_NORMAL = 'normal';
    const LEVEL_SLIVER = 'sliver';
    const LEVEL_GOLD = 'gold';


    protected $UUID;

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

    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /** Finds UUID by username id
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function findBy_Uuid_Fcm_Apn($condition)
    {
        return static::find()->where([
            'and',
            [
                'or',
                ['username' => $condition],
                ['email' => $condition],
                ['id' => $condition],
                ['uuid' => $condition]
            ],
            ['status' => self::STATUS_ACTIVE]
        ])->one();
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameForEmployee($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE, 'employee' => [1, 2]]); //  1,2 Là Nhân viên , 0 là khách hàng
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
     * @param $id
     * @return User|null
     */
    public static function findByUuid($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $client_id_ga
     * @return User|null
     */
    public static function findClientidga($client_id_ga)
    {
        return static::findOne(['client_id_ga' => $client_id_ga, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $id
     * @param $token_fcm
     * @return User|null
     */
    public static function findByTokenFcm($id, $token_fcm)
    {
        return static::findOne(
            [
                'id' => $id,
                //'token_fcm'=> $token_fcm,
                'status' => self::STATUS_ACTIVE
            ]);
    }

    /**
     * @param $id
     * @param $token_apn
     * @return User|null
     */
    public static function findByTokenApn($id, $token_apn)
    {
        return static::findOne(['id' => $id, 'token_apn' => $token_apn, 'status' => self::STATUS_ACTIVE]);
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
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

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
        return $this->id;
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
     * Generates  token_verifiy_user_create_new
     */
    public function generateTokenVerifiyUserCreateNew()
    {
        $token_verifiy_user_create_new = Yii::$app->security->generateRandomString() . '_' . time();
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
            'employee' => $this->employee,
            'email' => $this->email,
            'role' => !empty($role) ? array_keys($role) : [],
            'permission' => !empty($permissions) ? array_keys($permissions) : []
        ];
    }

    public static function findAdvance($condition)
    {
        return static::find()->where([
            'and',
            [
                'or',
                ['username' => $condition],
                ['email' => $condition],
                ['phone' => $condition]
            ],
            ['status' => self::STATUS_ACTIVE],
            ['store_id' => Yii::$app->storeManager->storeId]
        ])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasMany(Address::className(), ['customer_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryAddress()
    {
        return $this->getAddress()->andWhere(['type' => Address::TYPE_PRIMARY]);
    }

    /**
     * @return null
     */
    public function getShippingAddress()
    {
        return $this->getAddress()->andWhere(['type' => Address::TYPE_SHIPPING]);
    }


    public function getDefaultPrimaryAddress()
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

    /**
     * @return string
     */
    public function getUserLevel()
    {
        if ($this->vip === null && $this->vip <= 2) {
            return self::LEVEL_NORMAL;
        } else if ($this->vip < 2 && $this->vip <= 4) {
            return self::LEVEL_SLIVER;
        } else {
            return self::LEVEL_GOLD;
        }
    }

    public function getFingerprint()
    {
        return $this->id . 'WS' . $this->email;
    }

    /**
     * @return Warehouse|null
     */
    public function getPickupWarehouse()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getAuthAssigments()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return null
     */
    public function getScopeAuth()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getCurrencyCode()
    {
        switch ($this->store_id) {
            case 1:
                return 'VND';
                break;
            case 7:
                return 'IDR';
                break;
            default:
                return 'VN';
                break;
        }
    }

    public function getCountryCode()
    {
        switch ($this->store_id) {
            case 1:
                return 'VN';
                break;
            case 7:
                return 'ID';
                break;
            default:
                return 'VN';
                break;
        }
    }

    public function setCookiesUser()
    {
        $cookieUser = new UserCookies();
        $cookieUser->facebook_id = $this->facebook_acc_kit_id;
        $cookieUser->facebook_token = $this->facebook_acc_kit_token;
        $cookieUser->name = $this->last_name . ' ' . $this->first_name;
        $cookieUser->phone = $this->phone;
        $cookieUser->email = $this->email;
//      $cookieUser->uuid = $this->getUuidCookie();
        $cookieUser->customer_id = $this->id;
        if ($this->primaryAddress && count($this->primaryAddress) > 0) {
            $cookieUser->country_id = $this->primaryAddress[0]->country_id;
            $cookieUser->province_id = $this->primaryAddress[0]->province_id;
            $cookieUser->district_id = $this->primaryAddress[0]->district_id;
            $cookieUser->address = $this->primaryAddress[0]->address;
        }
        $cookieUser->setNewCookies();
    }
}
