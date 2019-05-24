<?php


namespace wallet\controllers;


use common\filters\Cors;
use common\models\AccessTokens;
use common\models\AuthorizationCodes;
use common\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

class BackendController extends Controller
{
    public $post;
    public $get;
    public $request;
    public $user;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->request = Yii::$app->request;
        $this->post = $this->request->post();
        $this->get = $this->request->get();
        $code = $this->GetResponseCodeAuth();
        if ($code == 200) {
            return parent::beforeAction($action);
        }
        Yii::$app->response->data = $this->response(false, 'Check your auth.', [], $code, 0);
        Yii::$app->response->statusCode = $code;
        Yii::$app->response->send();
//            Yii::$app->response->st;
        return false;
    }
    public function GetResponseCodeAuth(){
        $authorToken = $this->request->getHeaders()['authorization'];
        if(!empty($authorToken)){
            /** @var AccessTokens $token */
            $token = AccessTokens::find()->where(['token' => $authorToken])->one();
            if($token && $token->expires_at && $token->expires_at > time()){
                $this->user = User::findOne($token->user_id);
                if($this->user && $this->user->employee == User::EMPLOYEE){
                    foreach ($this->user->authAssigments as $assigment){
                        if(in_array($assigment,['superAdmin','operation','accountant','master_accountant','master_sale','sale','master_operation'])){
                            return 200;
                            break;
                        }
                    }
                    return 403;
                }
            }
        }
        return 401;
    }
    public function response($success = false, $message = null, $data = null, $code = null, $total = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res['success'] = $success;
        $res['message'] = $message;
        $res['total'] = $total;
        $res['code'] = $code;
        if (is_object($data)) {
            $res['data'] = $data->getAttributes();
        } else {
            $res['data'] = $data;
        }
        return $res;
    }

    public function renderJSON($success = false, $message = null, $data = null, $total = null)
    {
        return $this->response($success, $message, $data, null, $total);
//        header('Content-Type: application/json');
//        $res['success'] = $success;
//        $res['message'] = $message;
//        $res['total'] = $total;
//        $res['data'] = $data;
//
//        echo json_encode($res, JSON_PRETTY_PRINT);
    }
}