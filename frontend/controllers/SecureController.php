<?php


namespace frontend\controllers;

use common\models\Customer;
use Yii;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use app\models\User;
use yii\web\BadRequestHttpException;

/****APP Call Back FaceBook Google etc *****/
use common\components\AuthHandler;

class SecureController extends FrontendController
{

    public $layout = 'secure';

    public function actions()
    {
        $actions = parent::actions();
        $actions['auth'] = [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ];
         return $actions;
    }

    /**
     * Logs in a user.
     * @return mixed
     */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * @param $client
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
