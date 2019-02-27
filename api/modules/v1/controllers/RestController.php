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
        //            Yii::$app->api->sendFailedResponse('Invalid Access token');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    }

    public function response($success = false, $message = null, $data = null,$statusCode = 200)
    {
        Yii::$app->response->format = 'json';
        Yii::$app->response->setStatusCode($statusCode);
        $message = is_null($message) ? "" : $message;
        return Response::json($success, $message, $data);
    }
}


