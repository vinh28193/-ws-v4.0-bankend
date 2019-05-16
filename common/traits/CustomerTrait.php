<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-09
 * Time: 15:56
 */

namespace common\traits;

use common\helpers\CustomerHelper;
use Firebase\JWT\JWT;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Trait CustomerTrait
 * @package common\traits
 * @inheritdoc
 */
trait CustomerTrait
{

    public function getStoreComponent()
    {
        $cpn = Yii::$app->store;
        if (property_exists($this, 'storeId')) {
            return $cpn->tryStore($this->storeId);
        }
        return $cpn;
    }

    public function setPasswordBySalt($password, $regenerateSalt = true)
    {
        $salt = $this->salt;
        if ($salt === null || $regenerateSalt) {
            $this->salt = Yii::$app->getSecurity()->generateRandomString(13);
        }
        $this->password = sha1($password . $salt);
    }

    public function getVerifyToken($regenerate = false)
    {
        $verifyToken = $this->verifyToken;
        if ($verifyToken === null || $regenerate) {
            $verifyToken = Yii::$app->getSecurity()->generateRandomString(35);
            $this->verifyToken = $verifyToken;
        }
        return $verifyToken;
    }

    public function generateVerifyToken()
    {
        $this->getVerifyToken(true);
    }

    /**
     * @param string $type
     * @param bool $regenerate
     * @return string
     */
    public function getVerifyCode($type = null,$regenerate = false)
    {
        $rpc = $this->verify_code;
        if ($rpc === null || $regenerate) {
            $rpc = CustomerHelper::generateVerifyCode();
            $this->verify_code = $rpc;
            $this->verify_code_expired_at = Yii::$app->formatter->asTimestamp('now +  1 days');
            $this->verify_code_count = 1;
            $this->verify_code_type = $type;
            if ($regenerate) {
                $this->update(false, ['verify_code', 'verify_code_expired_at','verify_code_count', 'verify_code_type']);
            }
        }
        return $rpc;
    }

    /**
     * @return string
     */
    public function getExpire()
    {
        return Yii::$app->getFormatter()->asDatetime($this->verify_code_expired_at);
    }

    /**
     * @param $code
     * @param string $type
     * @return array
     */
    public function verify($code)
    {
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        if ($now > $this->verify_code_expired_at) {
            return [false, Yii::t('frontend', 'Time Expired')];
        }
        $message = Yii::t('frontend', 'Valid');
        if (($valid = $code === $this->getVerifyCode()) === false) {
            $message = Yii::t('frontend', 'Failed');
        }
        return [$valid, $message];
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $local
     * @param bool $force
     * @return mixed
     */
    public function updateLocale($local, $force = true)
    {
        $this->locale = $local;
        if ($force) {
            $this->update(false, ['locale']);
        }
        return $this->locale;
    }

    /**
     * @return bool
     */
    public function isSystemAccount()
    {
        return ArrayHelper::isIn($this->getId(), [1, 2, 3]);
    }

    public function getJwtEncode()
    {
        return \common\helpers\JwtHelper::encode([
            'iss' => $this->getStoreComponent()->getUrl(),
            'sub' => $this->email ? $this->email : $this->username,
            'aud' => $this->getVerifyToken(),
            'is_active' => $this->active ? true : false,
            'iat' => Yii::$app->formatter->asTimestamp('now'),
            'nbf' => Yii::$app->formatter->asTimestamp('now'),
            'exp' => Yii::$app->formatter->asTimestamp('now + 2 days'),
        ]);
    }

    public static function verifyJwt($jwt)
    {
        return \common\helpers\JwtHelper::validate($jwt);
    }
}