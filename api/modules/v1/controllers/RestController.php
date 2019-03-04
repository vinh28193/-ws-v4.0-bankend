<?php

namespace api\modules\v1\controllers;

use common\components\Response;
use Yii;
use yii\rest\Controller;



class RestController extends Controller
{

    public $post;
    public $get;

    public $enableCsrfValidation = false;

    public $headers;

    public $type_user = 'user';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                // 'Access-Control-Allow-Origin' => ['*', 'http://haikuwebapp.local.com:81','http://localhost:81'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*','http://localhost:4200'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => []
            ]

        ];
        return $behaviors;
    }

    public function init()
    {
        $this->post = Yii::$app->request->post();
        $this->get = Yii::$app->request->get();
        if($this->post&&!is_array($this->post)){
            Yii::$app->api->sendFailedResponse(['Invalid Json']);
        }
        $arr_tmp = explode("/",Yii::$app->request->url);
        $type = isset($this->get['name']) && $this->get['name'] ? $this->get['name'] : 'user';
        switch ($type){
            case 'user':
            case 'api':
                \Yii::$app->user->identityClass = 'common\models\User';
                $this->type_user = 'user';
                break;
            case 'userbackend':
            case 'weshop':
            case 'customer':
                \Yii::$app->user->identityClass = 'common\models\Customer';
                $this->type_user = 'customer';
                break;
            default:
                $this->type_user = 'user';
                \Yii::$app->user->identityClass = 'common\models\User';
                break;
        }
        //            Yii::$app->api->sendFailedResponse('Invalid Access token');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    }

//    public function response($success = false, $message = null, $data = null,$statusCode = 200)
//    {
//        Yii::$app->response->format = 'json';
//        Yii::$app->response->setStatusCode($statusCode);
//        $message = is_null($message) ? "" : $message;
//        return Response::json($success, $message, $data);
//    }

    public function response($success = false, $message = null, $data = null,$total = 0, $statusCode = 200)
    {
        $res['success'] = $success;
        $res['message'] = $message;
        $res['status_code'] = $statusCode;
        $res['total'] = $total;
        if (is_object($data)) {
            $res['data'] = $data->getAttributes();
        } else {
            $res['data'] = $data;
        }
        \Yii::$app->response->setStatusCode($statusCode);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        echo json_encode($res);
         \Yii::$app->response->data  =   $res;
        \Yii::$app->response->send();
        exit();
    }
    public function isCustomer(){
        return $this->type_user == 'customer';
    }
}


