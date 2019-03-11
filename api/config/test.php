<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/test_db.php');
return [
    'id' => 'app-backend-tests',
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],
                // etc.
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
//        'response' => [
//            'format' => yii\web\Response::FORMAT_JSON,
//            'charset' => 'UTF-8',
//            'on beforeSend' => function ($event) {
//               /*
//                $_user_Identity = Yii::$app->user->getIdentity();
//                $_user_id = $_user_Identity->getId();
//                $_user_email = $_user_Identity['email'];
//                $_user_AuthKey = $_user_Identity->getAuthKey();
//                $_user_name = $_user_Identity['username'];
//
//                //----ToDo Need More Infor param
//                $_user_app = 'Weshop2019';
//                $_user_request_suorce = "WEB_API_FRONTEND";//"APP/WEB_API_FRONTEND/WB_API_BACK_END"
//                $_request_ip = "127.0.0.1";
//               */
//
//                $response = $event->sender;
//                $_data = $response->data;
//                if ($response->data !== null) {
//                    $response->data = [
//                        'success' => $response->isSuccessful,
//                        'timestamp' => time(),
//                        'path' => Yii::$app->request->getPathInfo(),
//                        'data' => $response->data,
//                    ];
//
//                    /*
//                    $_rest_data = [ "RestApiCall" => [
//                        "success" => $response->isSuccessful,
//                        "timestamp" => time(),
//                        "path" => @json_encode(Yii::$app->request->getPathInfo()),
//                        "data" => @json_encode($_data),
//                        "date" =>date('Y-m-d H:i:s'),
//                        "user_id" => $_user_id,
//                        "user_email" => $_user_email,
//                        "user_name" => $_user_name,
//                        "user_app" =>$_user_app,
//                        "user_request_suorce" => $_user_request_suorce,
//                        "request_ip" => $_request_ip
//                    ]];
//
//                    $rest_model = new RestApiCall();
//                    if ($rest_model->load($_rest_data) && $rest_model->save()) {
//                        $id = (string)$rest_model->_id;
//                    } else {}
//                    */
//                }
//            },
//        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'class' => \common\components\ErrorHandler::className()
        ],
        'urlManager' => require(__DIR__ . '/urlManager.php'),
    ],
    'params' => $params,
];
