<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Change password form for current user only
 */
class ChangePasswordForm extends Model
{
    public $password_hash;
    public $passwordNew;
    public $replacePassword;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password_hash', 'passwordNew', 'replacePassword'], 'required'],
            [['password', 'replacePassword'], 'string', 'min' => 6],
            ['replacePassword', 'compare', 'compareAttribute' => 'password'],
        ];
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


    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $value = $this->$attribute;
            if ($this->getUser()->validatePassword($value)) {
                $this->addError($attribute, "");
            }
        }
    }

    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->getUser()->setPassword($this->replacePassword);
        return $this->getUser()->update(false);
    }
}