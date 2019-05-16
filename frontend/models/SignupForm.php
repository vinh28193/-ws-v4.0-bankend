<?php
namespace frontend\models;

use common\models\Customer;
use yii\base\Model;
use common\models\User;
use Yii;
use common\models\Auth;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $last_name;
    public $first_name;
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
            ['last_name', 'string', 'min' => 2, 'max' => 255],
            ['phone', 'string', 'min' => 10, 'max' => 15],
            // ['phone', 'countryValue' => 'US'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Customer', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8,'max'=>72],

            ['replacePassword', 'compare', 'compareAttribute' => 'password'],

            ['first_name', 'trim'],
            ['first_name', 'required'],
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
        @date_default_timezone_set('Asia/Ho_Chi_Minh');
        $Customer = new User([
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'username' => $this->email,
            'email' => $this->email,
            'password' => $this->password,
            'updated_at'=> time(),
            'store_id' => 1, // Domain Weshop Viet Nam
            'active_shipping' => 0,
            'total_xu' => 0,
            'usable_xu' => 0 ,
            'last_revenue_xu' => 0 ,
            'email_verified' => 1 ,  // Nếu là Google Email lấy được rồi nên coi như là dã xác nhận
            'phone_verified' => 0, // Vì là đăng kí qua Google + Facebook nên chưa xác nhân hoặc lấy được số đt của khách hàng
            'gender' => 0 ,
            'type_customer' => 1 , // set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer
            'avatar' => null ,
            'note_by_employee' => 'Khách hàng tạo tài khoản qua theo cách bình thường qua link Weshop',
        ]);

        $Customer->setPassword($this->password);
        $Customer->generateAuthKey();
//        $Customer->generateToken();
//        $Customer->generateAuthClient();
//        $Customer->generateXu();

        if ($Customer->save()) {
            $auth = new Auth([
                'user_id' => $Customer->id,
                'source' => null,
                'source_id' => $Customer->id.'2019',
            ]);

            if ($auth->save()) {
                Yii::$app->user->login($Customer, 0);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Error save Auth');
            }

        } else {
            Yii::$app->getSession()->setFlash('error', [
                Yii::t('app', 'Unable to save Customer : {errors}'),
            ]);
        }
    }
}
