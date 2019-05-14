<?php
namespace common\components;

use common\models\Auth;
use common\models\User;
use common\models\Customer;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthCustomerHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        @date_default_timezone_set('Asia/Ho_Chi_Minh');

        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'login');
        $duration = isset(Yii::$app->params['user.rememberMeDuration']) ? isset(Yii::$app->params['user.rememberMeDuration']) : 3600;

        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($email !== null && Customer::find()->where(['email' => $email])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                ]);
            } else {
                $password = Yii::$app->security->generateRandomString(6);
                $user = new Customer([
                    'username' => $nickname ? $nickname : $email,
//                        'github' => $nickname,
                    'email' => $email,
                    'password' => $password,
                    'updated_at'=> time(),
                ]);

                $user->generateAuthKey();
                $user->generatePasswordResetToken();

                $transaction = Customer::getDb()->beginTransaction();

                if ($user->save()) {
                    $transaction->commit();
                    Yii::$app->user->login($user, $duration);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to save user: {errors}'),
                    ]);
                }
            }
        } else {
            Yii::$app->getSession()->setFlash('success', [
                Yii::t('app', 'Linked {client} account.', [
                    'client' => $this->client->getTitle()
                ]),
            ]);
        }
    }

    /**
     * @param Customer $user
     */
//    private function updateUserInfo(Customer $user)
//    {
//        $attributes = $this->client->getUserAttributes();
//        $github = ArrayHelper::getValue($attributes, 'login');
//        if ($user->github === null && $github) {
//            $user->github = $github;
//            $user->save();
//        }
//
//    }
}
