<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 23/02/2019
 * Time: 09:59
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\LoginCustomerForm;
use Yii;
use common\models\LoginForm;
use common\models\AuthorizationCodes;
use common\models\AccessTokens;

use backend\models\SignupForm;


class LoginController extends BaseApiController
{
    /** @var LoginForm $loginForm */
    public $loginForm;
    /** @var SignupForm $sigUpForm */
    public $sigUpForm;

    public $expiredLogin = null;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        switch ($this->type_user){
            case "user":
                $this->loginForm = new LoginForm();
                $this->sigUpForm = new SignupForm();
                break;
            case "customer":
                $this->loginForm = new LoginCustomerForm();
                $this->sigUpForm = new \userbackend\models\SignupForm();
                $this->expiredLogin = LoginCustomerForm::EXPIRED_LOGIN;
                break;
            default:
                $this->loginForm = new LoginForm();
                $this->sigUpForm = new SignupForm();
                break;
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->api->sendSuccessResponse(['WESHOP @2019 RESTful API with OAuth2']);
        exit();
        //  return $this->render('index');
    }

    public function actionRegister()
    {

        $model = $this->sigUpForm;
        $model->attributes = $this->post;

        if ($user = $model->signup()) {

            $data=$user->attributes;
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

    public function actionAccesstoken()
    {
        if (!isset($this->post["authorizationCode"])) {
            return $this->response(false,"Authorization code missing",[],0,401);
//            Yii::$app->api->sendFailedResponse("Authorization code missing");
        }

        $authorization_code = $this->post["authorizationCode"];

        $auth_code = AuthorizationCodes::isValid($authorization_code);
        if (!$auth_code) {
            return $this->response(false,"Invalid Authorization Code",[],0,401);
        }

        $accesstoken = Yii::$app->api->createAccesstoken($authorization_code,$this->type_user,$this->expiredLogin);

        $data = [];
        $data['access_token'] = $accesstoken->token;
        $data['expires_at'] = $accesstoken->expires_at;
        return $this->response(true,"success",$data);

    }

    public function actionAuthorize()
    {
        $model = $this->loginForm;
        $model->attributes = $this->post;
        $model->login();
        if ($model->validate() && $model->login()) {

            $auth_code = Yii::$app->api->createAuthorizationCode(Yii::$app->user->identity['id'],$this->type_user,$this->expiredLogin);

            $data = [];
            $data['authorization_code'] = $auth_code->code;
            $data['user'] = Yii::$app->user->getIdentity();
            $data['expires_at'] = $auth_code->expires_at;
            return $this->response(true,"Login success!",$data);
        } else {
            $mess = "";
            foreach ($model->errors as $error){
                $mess .= $error[0]." .";
            }
            return $this->response(false,$mess,$model->errors);
        }
    }

    public function actionLogout()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        $access_token = $headers->get('x-access-token');

        if(!$access_token){
            $access_token = Yii::$app->getRequest()->getQueryParam('access-token');
        }

        $model = AccessTokens::findOne(['token' => $access_token]);

        if ($model->delete()) {

            Yii::$app->api->sendSuccessResponse(["Logged Out Successfully"]);

        } else {
            Yii::$app->api->sendFailedResponse("Invalid Request");
        }


    }
}