<?php


namespace frontend\models;

use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Customer;

class LoginForm extends Model
{


    public $loginId;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['loginId', 'password'], 'required'],
            ['loginId', 'trim'],
            ['loginId', 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['password', 'required'],
        ];
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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('frontend', 'Incorrect email or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            $success = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            $this->getUser()->setCookiesUser();
//            if ($success) {
//                $walletService = new WalletService();
//                $success = $walletService->login($this->password);
//            }
            return $success;
        }

        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findAdvance($this->loginId);
        }
        return $this->_user;
    }
}
