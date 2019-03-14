<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 17:14
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;
use api\v1\models\AccessTokenForm;
use api\v1\models\AuthorizeForm;
use common\components\AuthHandler;
use common\models\AccessTokens;
use common\models\AuthorizationCodes;

class SecureController extends BaseApiController
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                'allow' => true,
                'actions' => ['auth', 'authorize', 'register', 'access-token'],
                'roles' => ['*'],
            ]
        ]);
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


    public function actionMe()
    {
        /** @var  $user \common\components\UserPublicIdentityInterface */
        if (($user = Yii::$app->user->identity) === null) {
            return $this->response(false, "user not login", $user->getPublicIdentity());
        }
        return $this->response(true, "get public identity is success", $user->getPublicIdentity());
    }

    public function actionAccessToken()
    {

        $model = new AccessTokenForm();
        $model->load($this->post, '');
        /** @var  $handler boolean|AuthorizationCodes */
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
        /** @var  $authorize boolean|\common\models\AuthorizationCodes */
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