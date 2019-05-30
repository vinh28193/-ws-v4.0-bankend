<?php


namespace frontend\models;

use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use common\models\User;
use yii\base\Model;

class PasswordRequiredForm extends Model
{
    /**
     * @var
     */
    public $password;

    /**
     * @var User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'validatePassword'],

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
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    public function reLogin()
    {
        if ($this->validate()) {
            $walletService = new WalletService();
            return $walletService->login($this->password);
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
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }
}