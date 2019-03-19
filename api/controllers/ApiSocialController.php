<?php

namespace api\controllers;


use yii\filters\AccessControl;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;

use Yii;



class ApiSocialController extends BaseApiController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [

                'apiauth' => [
                    'class' => Apiauth::className(),
                    'exclude' => ['convert-token'],
                    'callback'=>[]
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => [
                                'index'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['convert-token'],
                            'allow' => true,
                            'roles' => ['*'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => Verbcheck::className(),
                    'actions' => [
                        'index' => ['GET', 'POST'],
                        'convert-token' => ['POST'],
                    ],
                ],

            ];
    }
/**
* @inheritdoc
*/
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->api->sendSuccessResponse(['WESHOP @2019 RESTful ApiSocial API with OAuth2']);
    }

    public function actionConvertToken()
    {
        Yii::$app->api->sendSuccessResponse(['WESHOP @2019 RESTful convert-token POST ApiSocial API with OAuth2']);
    }

}
