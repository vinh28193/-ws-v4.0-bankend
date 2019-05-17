<?php


namespace frontend\controllers;

use common\models\Customer;
use Yii;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use app\models\User;
use yii\web\BadRequestHttpException;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;

/****APP Call Back FaceBook Google etc *****/
use common\components\AuthCustomerHandler;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                ],
            ],
        ];
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
        $model->rememberMe = false; // Mặc định không ghi nhớ
        if ($model->load(Yii::$app->request->post()) && $model->login() ) {
            $url_rel = Yii::$app->request->get('rel','/');
             return Yii::$app->getResponse()->redirect($url_rel);
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
        Yii::info('register new');
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                $model->sendEmail();
                if(Yii::$app->getUser()->login($user)){ return $this->goHome(); }
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
        (new AuthCustomerHandler($client))->handle();
    }

    public function actionRequestPasswordReset()
    {
        Yii::info('RequestPasswordReset');
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::info('success', 'Check your email for further instructions.');
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                //return $this->goHome();
                return Yii::$app->getResponse()->redirect('/secure/login');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('requestPasswordReset', [
            'model' => $model,
        ]);

    }

    // ToDo Làm tiếp sau nay khi có thời gian
    /** https://weshop-v4.front-end-ws.local.vn/secure/verify?token=ZIieKz6RnbB8Bp0MfQcWrX7xId_v5VhF
     * Verify Account Register New
     * @param $token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerify($token)
    {}

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        Yii::info('ResetPassword.');
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            Yii::info('success', 'ResetPassword.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
