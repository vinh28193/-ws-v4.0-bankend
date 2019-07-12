<?php
namespace frontend\models;

use common\models\Customer;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
//                'filter' => ['active' => User::STATUS_ACTIVE],
                'message' => Yii::t('frontend', 'There is no user with this email address.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        Yii::info('Send email reset password');
        /** @var  $mailer yii\mail\BaseMailer */
        $mailer = Yii::$app->mandrillMailer;
        $mailer->viewPath = '@common/mail';
        $mail = $mailer->compose(
            ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
            ['user' => $user]
        );
        $from = [Yii::$app->storeManager->store->country_code === 'ID' ? 'no-reply@weshop.co.id' : 'no-reply@weshop.com.vn' => Yii::$app->storeManager->store->name];
        $mail->setFrom($from);
        $mail->setTo($this->email);
        $mail->setSubject(Yii::t('frontend','Password reset for {web_name}',['web_name' => Yii::$app->name]));
        return $mail->send();
    }

    public  function FindEmail()
    {
        /* @var $user User */
        $cus = User::findOne([
            'email' => $this->email,
        ]);
        return $cus;
    }
}
