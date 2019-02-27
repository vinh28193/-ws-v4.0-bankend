<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;



class RestController extends Controller
{

    public $get;
    public $post;

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
                'Access-Control-Request-Headers' => ['*'],
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

    }

}


