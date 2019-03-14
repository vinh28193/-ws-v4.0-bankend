<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-13
 * Time: 17:03
 */

namespace api\v1\models;

use common\models\AuthorizationCodes;
use Yii;
use yii\base\Model;

class AccessTokenForm extends Model
{

    public $authorization_code;

    private $_authorization;

    private $identityClass;

    public function rules()
    {
        return [
            ['authorization_code', 'required'],
            ['authorization_code', 'validateAuthorizationCode'],
        ];
    }

    public function init()
    {
        parent::init();
        $this->identityClass = Yii::$app->user->identityClass;
    }

    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        $errors = reset($errors);
        return $errors;
    }

    public function handle()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var  $class \yii\web\IdentityInterface */
        $class = $this->identityClass;
        $authorization = $this->getAuthorization();
        /** @var  $identity \yii\web\IdentityInterface|\common\components\UserPublicIdentityInterface */
        $identity = $class::findIdentity($authorization->user_id);
        /** @var  $accessToken \common\models\AccessTokens*/
        $accessToken = Yii::$app->api->createAccesstoken($authorization->code);
        Yii::debug("authorization code {$authorization->code} handle access token {$accessToken->token}", __METHOD__);
        Yii::$app->getUser()->login($identity);
        return [
            'accessToken' => $accessToken,
            'userPublicIdentity' => $identity->getPublicIdentity(),
        ];
    }

    public function validateAuthorizationCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $value = $this->$attribute;
            if (($authorization = $this->getAuthorization()) === null) {
                $this->addError($attribute, "Not found authorization code `$value`");
            } elseif (!$authorization->isValid()) {
                $this->addError($attribute, 'Authorization code is expire');
            }
        }
    }

    protected function getAuthorization()
    {
        if ($this->_authorization === null) {
            $this->_authorization = AuthorizationCodes::findOne(['code' => $this->authorization_code]);
        }
        return $this->_authorization;
    }
}