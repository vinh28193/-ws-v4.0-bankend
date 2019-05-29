<?php
namespace frontend\models;

use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Change password form for current user only
 */
class ChangePasswordForm extends Model
{
    public $passwordOld;
    public $passwordNew;
    public $confirm_password;

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
            [['passwordOld','passwordNew', 'replacePassword'], 'required'],
            [['password','confirm_password'], 'string', 'min' => 6],
            ['replacePassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Changes password.
     *
     * @return boolean if password was changed.
     */
//    public function findPasswords($attribute, $params)
//    {
//        $user = User::model()->findByPk(Yii::app()->user->id);
//        $us = User::
//        if ($user->password != md5($this->old_password))
//            $this->addError($attribute, 'Old password is incorrect.');
//    }
}