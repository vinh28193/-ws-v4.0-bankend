<?php


namespace frontend\controllers;

use common\models\Customer;
use Yii;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use app\models\User;
use yii\web\BadRequestHttpException;

class SecureController extends FrontendController
{

    public $layout = 'secure';

    /**
     * Logs in a user.
     * @return mixed
     */

    public function actionLogin(){

        if (!Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if ($model->load($this->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }

        $model = new LoginForm();
        if ($model->load($this->request->post()) && $model->login()) {
            return $this->goBack();
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
}