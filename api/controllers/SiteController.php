<?php

namespace api\controllers;

use api\models\SignupForm;
use common\components\AuthHandler;
use common\models\AccessTokens;
use common\models\AuthorizationCodes;
use common\models\LoginForm;
use Yii;

/****APP Call Back FaceBook Google etc *****/

/**
 * Site controller
 */
class SiteController extends BaseApiController
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//
//        $behaviors = parent::behaviors();
//
//        return $behaviors + [
//                'apiauth' => [
//                    'class' => Apiauth::className(),
//                    'exclude' => ['authorize', 'register', 'accesstoken', 'index'],
//                ],
//                'access' => [
//                    'class' => AccessControl::className(),
//                    'only' => ['logout', 'signup'],
//                    'rules' => [
//                        [
//                            'actions' => ['signup'],
//                            'allow' => true,
//                            'roles' => ['?'],
//                        ],
//                        [
//                            'actions' => ['logout', 'me'],
//                            'allow' => true,
//                            'roles' => ['@'],
//                        ],
//                        [
//                            'actions' => ['authorize', 'register', 'accesstoken'],
//                            'allow' => true,
//                            'roles' => ['*'],
//                        ],
//                    ],
//                ],
//                'verbs' => [
//                    'class' => Verbcheck::className(),
//                    'actions' => [
//                        'logout' => ['GET'],
//                        'authorize' => ['POST'],
//                        'register' => ['POST'],
//                        'accesstoken' => ['POST'],
//                        'me' => ['GET'],
//                    ],
//                ],
//            ];
//    }


    public function rules()
    {
        return [
            [
                'actions' => ['signup'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'actions' => ['logout', 'me'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'actions' => ['authorize', 'register'],
                'allow' => true,
                'roles' => ['*'],
            ]
        ];
    }

    public function verbs()
    {
        return array_merge([
            'logout' => ['GET', 'OPTIONS'],
            'authorize' => ['POST', 'OPTIONS'],
            'register' => ['POST', 'OPTIONS'],
            'access-token' => ['POST', 'OPTIONS'],
            'me' => ['GET', 'OPTIONS']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->api->sendSuccessResponse(['@Weshop Global 2019 - RESTful API with OAuth2']);
        //  return $this->render('index');
    }

    public function actionRegister()
    {

        $model = new SignupForm();
        $model->attributes = $this->post;

        if ($user = $model->signup()) {

            $data = $user->attributes;
            unset($data['auth_key']);
            unset($data['password_hash']);
            unset($data['password_reset_token']);

            Yii::$app->api->sendSuccessResponse($data);

        }

    }


    public function actionMe()
    {
        $data = Yii::$app->user->identity;
        $data = $data->attributes;
        unset($data['auth_key']);
        unset($data['password_hash']);
        unset($data['password_reset_token']);

        Yii::$app->api->sendSuccessResponse($data);
    }

    public function actionAccessToken()
    {
        if (!isset($this->post['authorization_code']) || ($code = $this->post['authorization_code']) === null || $code === '') {
            return $this->response(false, "missing parameter `authorization_code` when posting request");
        }
        if (($model = AuthorizationCodes::findOne(['code' => $code])) === null) {
            // Todo Yii::t
            return $this->response(false, "not found authorization code `$code`");
        }
        if (!$model->isValid()) {
            return $this->response(false, "$code is expired");
        }
        // Todo Yii::t
        $class = Yii::$app->user->identityClass;
        Yii::$app->user->setIdentity($class::findIdentity($model->user_id));
        return $this->response(true, "login with $code is success", [
            'accessToken' => Yii::$app->api->createAccesstoken($model->code),
            'userPublicIdentity' => Yii::$app->user->identity->getPublicIdentity(),
        ]);

    }

    public function actionAuthorize()
    {
        $model = new LoginForm();
        $model->attributes = $this->post;
        $model->load($this->post, '');

        if ($model->validate() && $model->login()) {
            if (YII_DEBUG == true and YII_ENV == 'test') {
                $expired_time = 60 * 60 * 5;
                $type = 'user';
            } else {
                $expired_time = null;
                $type = 'user';
            }
            $authorizationCode = Yii::$app->api->createAuthorizationCode(Yii::$app->user->getId(), $type, $expired_time);
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute("/1/access-token/{$authorizationCode->code}", true));
            return $this->response(true, "success", $authorizationCode);
        } else {
            return $this->response(false, "failed", $model->errors);
        }
    }

    public function actionLogout()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        $access_token = $headers->get('x-access-token');

        if (!$access_token) {
            $access_token = Yii::$app->getRequest()->getQueryParam('access-token');
        }

        $model = AccessTokens::findOne(['token' => $access_token]);

        if ($model->delete()) {

            return $this->response(false, "Logout success");

        } else {
            return $this->response(false, "Logout error");
        }


    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
