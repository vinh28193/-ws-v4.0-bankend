<?php
namespace frontend\models;

use common\models\Customer;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $last_name;
    public $email;
    public $password;
    public $phone;
    public $replacePassword;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['last_name', 'trim'],
            ['last_name', 'required'],
            ['last_name', 'unique', 'targetClass' => '\common\models\Customer', 'message' => 'This last name has already been taken.'],
            ['last_name', 'string', 'min' => 2, 'max' => 255],
            ['phone', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Customer', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['replacePassword', 'compare', 'compareAttribute' => 'password'],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Customer();
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->setPassword($this->replacePassword);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
