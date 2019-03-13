<?php

namespace api\controllers;

use api\models\SignupForm;
use common\components\AuthHandler;
use common\models\AccessTokens;
use common\models\AuthorizationCodes;
use common\models\LoginForm;
use api\models\AuthorizeForm;
use api\models\AccessTokenForm;
use Yii;

/****APP Call Back FaceBook Google etc *****/

/**
 * Site controller
 */
class SiteController extends BaseApiController
{

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
            'logout' => ['GET'],
            'authorize' => ['POST'],
            'register' => ['POST'],
            'access-token' => ['POST'],
            'me' => ['GET']
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
        /** @var  $user \common\components\UserPublicIdentityInterface */
        if(($user = Yii::$app->user->identity) === null){
            return $this->response(false, "user not login", $user->getPublicIdentity());
        }
        return $this->response(true, "get public identity is success", $user->getPublicIdentity());
    }

    public function actionAccessToken()
    {

        $model = new AccessTokenForm();
        $model->load($this->post,'');
        /** @var  $handler boolean|\common\models\AuthorizationCodes*/
        if (($handler = $model->handle()) === false) {
            return $this->response(false, $model->getFirstErrors());
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
        return $this->response(true, "login is success", $handler);

    }

    public function actionAuthorize()
    {
        $model = new AuthorizeForm();
        $model->attributes = $this->post;
        $model->load($this->post, '');
        /** @var  $authorize boolean|\common\models\AuthorizationCodes*/
        if (($authorize = $model->authorize()) === false) {
            return $this->response(false, $model->getFirstErrors());
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
        $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute("/1/access-token/{$authorize->code}", true));
        return $this->response(true, "success", $authorize);
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
            Yii::$app->user->logout();
            return $this->response(true, "Logout success");
        } else {
            return $this->response(false, "Logout error");
        }


    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
