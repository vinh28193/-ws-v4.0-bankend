<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/26/2018
 * Time: 9:41 PM
 */

namespace wallet\modules\v1\controllers;

use wallet\controllers\ServiceController;

use wallet\modules\v1\models\WalletClient;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use wallet\modules\v1\filters\ErrorToExceptionFilter;

class RestController extends ServiceController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    public function actionToken()
    {
        $request = $this->module->getRequest();
        if(isset($request->request['username'])){
            if(strpos($request->request['username'],'@')===false){
                $user = WalletClient::findByUsername($request->request['username']);
            }else{
                $user=WalletClient::findByEmail($request->request['username']);
                $request->request['username']=$user->username;
            }
//            if ($user != null) {
//                $request->request['scope'] = $user->scope;
//            }
            $response = $this->module->getServer()->handleTokenRequest($request);
            if ($user != null) {
                $userData = new \stdClass();
                $userData->id = $user->id;
                $userData->userName = $user->username;
                $userData->email = $user->email;
                $userData->storeId = $user->store_id;
//                $userData->scope = $user->scope;
                return array_merge($response->getParameters(), ['user' => $userData]);
            }else return $response->getParameters();
        }else{
            $response = $this->module->getServer()->handleTokenRequest($request);
            return $response->getParameters();
        }

    }

    public function actionAuthorize()
    {
        $server = $this->module->getServer();
        $request = $this->module->getRequest();
        $response = $this->module->getResponse();
        $server->handleAuthorizeRequest($request, $response, true);
        return $server->getResponse();
    }

    public function actionResetpassword()
    {
        $mail = Yii::$app->request->post('email');
        $model = new PasswordResetRequestForm();
        $model->email = $mail;
        if ($model->validate()) {
            if ($model->sendEmail()) {
                return ['success' => true, 'message' => 'Recovery mail sent to ' . $mail];
            } else {
                return ['success' => false, 'message' => 'Sorry, we are unable to reset password for the provided email address.'];
            }
        }
    }

    public function actionRegister()
    {
        $user = json_decode(Yii::$app->request->post('user'), true);
        $model = new SignupForm();
        $model->username = $user['username'];
        $model->name = $user['name'];
        $model->password = $user['password'];
        $model->email = $user['email'];
        if ($user = $model->signup()) {
            return ['success' => true, 'message' => 'User create success. Wait for administrator active your account'];
        } else {
            return ['success' => false, 'message' => 'Sorry, we are unable to create account, Check again'];
        }
    }

    public function actionCheckresettoken()
    {
        $token = Yii::$app->request->post('token');
        $user = User::findByPasswordResetToken($token);
        if ($user!=null) {
            return ['success' => true, 'message' => 'Token success'];
        } else {
            return ['success' => false, 'message' => 'Invalid reset token'];
        }
    }

    public function actionNewpassword()
    {
        $password =Yii::$app->request->post('password');
        $token =Yii::$app->request->post('token');
        $user = User::findByPasswordResetToken($token);
        $user->setPassword($password);
        $user->removePasswordResetToken();
        if ($user->save(false)) {
            return ['success' => true, 'message' => 'Password changed!'];
        } else {
            return ['success' => false, 'message' => 'Error'];
        }
    }
}