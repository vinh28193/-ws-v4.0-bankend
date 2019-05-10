<?php

namespace userbackend\models;

use common\models\Customer;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Change password form for current user only
 */
class PasswordForm extends Model
{
    public $id;
    public $passwordOld;
    public $passwordNew;
    public $RepeatPassword;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($id, $config = [])
    {
        $this->_user = Customer::findIdentity($id);

        if (!$this->_user) {
            throw new InvalidParamException('Unable to find user!');
        }

        $this->id = $this->_user->id;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passwordOld', 'passwordNew', 'RepeatPassword'], 'required'],
            [['passwordOld', 'passwordNew', 'RepeatPassword'], 'string', 'min' => 6],
            ['RepeatPassword', 'compare', 'compareAttribute' => 'passwordNew'],
            [['passwordOld'], 'findPassword'],
        ];
    }

    /**
     * Changes password.
     *
     * @return boolean if password was changed.
     */
    public function findPassword($attribute, $params)
    {
        $id = Yii::$app->user->getIdentity()->getId();
        $cus = Customer::findOne($id);
        $hash2 = Yii::$app->security->validatePassword($this->passwordOld, $cus->password_hash);
        if (!$hash2) {
            $this->addError($attribute, 'Old password is incorrect.');
        }
    }

    public function changePassword()
    {
        $user = $this->_user;
        $user->setPassword($this->passwordNew);

        return $user->save(false);
    }
}