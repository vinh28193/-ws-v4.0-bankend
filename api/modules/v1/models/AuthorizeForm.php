<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-13
 * Time: 16:32
 */

namespace api\modules\v1\models;

use Yii;
use yii\base\Model;

class AuthorizeForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @var \common\models\User |\yii\web\IdentityInterface
     */
    private $_user;

    private $identityClass;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validateUsername()
            ['username', 'validateUsername'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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

    public function validateUsername($attribute, $params)
    {

        if (!$this->hasErrors()) {
            $value = $this->$attribute;
            if (($user = $this->getUser()) === null) {
                $this->addError($attribute, "Not found username `$value`.");
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * @return boolean|\common\models\AuthorizationCodes
     */
    public function authorize()
    {
        if(!$this->validate()){
            return false;
        }
        if (YII_DEBUG == true and YII_ENV == 'test') {
            $expired_time = 60 * 60 * 5;
            $type = 'user';
        } else {
            $expired_time = null;
            $type = 'user';
        }
        /** @var  $user \common\models\User ApiGlobalIdentityInterface|\yii\web\IdentityInterface */
        $user = $this->getUser();
        return Yii::$app->api->createAuthorizationCode($user->getId(), $type, $expired_time);
    }

    /**
     * Finds user by [[username]]
     *
     * @return \common\models\User ApiGlobalIdentityInterface|\yii\web\IdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            /** @var  $class \common\models\User ApiGlobalIdentityInterface */
            $class = $this->identityClass;
            $this->_user = $class::findByUsernameForEmployee($this->username);
        }

        return $this->_user;
    }
}
