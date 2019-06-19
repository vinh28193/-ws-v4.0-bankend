<?php


namespace frontend\controllers;

use common\models\User;
use frontend\models\PasswordRequiredForm;
use User\SignUpRequest;
use User\SignUpResponse;
use User\UserClient;
use Yii;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
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
                'only' => ['logout', 'signup', 'index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            if(!$user->bm_wallet_id){
                try{
                    $greeterClient = new UserClient('206.189.94.203:50053', [
                        'credentials' => \Grpc\ChannelCredentials::createInsecure(),
                    ]);
                    $request = new SignUpRequest();
                    $request->setCurrency($user->getCurrencyCode());
                    $request->setCountry($user->getCountryCode());
                    $request->setEmail($user->email);
                    $request->setFullname(trim($user->last_name.' '. $user->first_name));
                    $request->setPassword1($model->password);
                    $request->setPassword2($model->password);
                    $request->setPhone($user->phone);
                    $request->setPlatform("WESHOP");
                    $request->setPlatformUser($user->id);
                    list($reply, $status) = $greeterClient->SignUp($request)->wait();
                    /** @var SignUpResponse $reply */
                    if(!$reply->getError()){
                        $wallet_boxme = $reply->getData();
                        User::updateAll(['bm_wallet_id' => $wallet_boxme->getUserId()],['id' => $user->id]);
                    }
                }catch (\Exception $exception){}
            }
            $redirectUrl = Yii::$app->getHomeUrl();
            if (($url_rel = $this->request->get('rel')) !== null) {
                $url_rel = urldecode($url_rel);
                $redirectUrl = $url_rel && ($url_rel[0] == '/' || $url_rel[0] == '\\') ? '/' . $url_rel : $url_rel;
            }
            return Yii::$app->getResponse()->redirect($redirectUrl);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionPasswordRequired()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('login');
        }
        $model = new PasswordRequiredForm();
        if ($model->load(Yii::$app->request->post()) && $model->reLogin()) {
            return true;
        }
        return false;
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
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Check your email for further instructions.'));
                $model->sendEmail();
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionRegisterValidate()
    {
        $model = new SignupForm();
        $request = Yii::$app->getRequest();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isAjax && $request->isPost && $model->load($request->post())) {
            return \yii\bootstrap4\ActiveForm::validate($model);
        }
        return [];
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
            Yii::info('success', 'Vui lòng kiểm tra email để lấy link reset mật khẩu tài khoản.');
            $model->sendEmail();
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'A link to reset password sent to your e-mail `{email}`, please check', [
                'email' => $model->email,
            ]));
            //return $this->goHome();
            return Yii::$app->getResponse()->redirect('/secure/login');
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
    {
        $user = new User();
        $token_Veriy_User_new = $user->generateTokenVerifiyUserCreateNew();
    }

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
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'New password saved'));
            Yii::info('success', 'ResetPassword.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $userId = Yii::$app->user->getId();
        $model = User::find()->where(['id' => $userId])->one();


        if (isset($_POST['User'])) {

            $model->attributes = $_POST['User'];
            $valid = $model->validate();

            if ($valid) {

                $model->password_hash = md5($model->passwordNew);

                if ($model->save())
                    $this->redirect(array('change-password', 'msg' => 'successfully changed password'));
                else
                    $this->redirect(array('change-password', 'msg' => 'password not changed'));
            }
        }

        $this->render('changePassword', array('model' => $model));
    }
    public function actionTestAuth(){
        print_r(Yii::$app->request->post());
        print_r(Yii::$app->request->get());
        die('test');
        $app_id = '216590825760272';
        $secret = '<account_kit_app_secret>';
        $version = 'v1.0';
        function doCurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch), true);
            curl_close($ch);
            return $data;
        }
        $token_exchange_url = 'https://graph.accountkit.com/' . $version . '/access_token?' .
            'grant_type=authorization_code' .
            '&code=' . $_POST['code'] .
            "&access_token=AA|$app_id|$secret";
        $data = doCurl($token_exchange_url);
        $user_id = $data['id'];
        $user_access_token = $data['access_token'];
        $refresh_interval = $data['token_refresh_interval_sec'];
        $me_endpoint_url = 'https://graph.accountkit.com/' . $version . '/me?' .
            'access_token=' . $user_access_token;
        $data = doCurl($me_endpoint_url);
        $phone = isset($data['phone']) ? $data['phone']['number'] : '';
        $email = isset($data['email']) ? $data['email']['address'] : '';


        print_r(Yii::$app->request->post());
        print_r(Yii::$app->request->get());
        die('test');
    }
}
