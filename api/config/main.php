<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
use backend\models\Logrouteapi;
use common\components\RestApiCall;

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['log', 'v1'], //,'v1/payment'
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
            'modules' => [
                'api' => [
                    'class' => 'api\modules\v1\api\Module',
                ],
                'weshop' => [
                    'class' => 'api\modules\v1\weshop\Module',
                    'modules' => [
                        'customer' => [
                            'class' => 'api\modules\v1\weshop\customer\Module',
                        ],
                    ],
                ],
                'userbackend' => [
                    'class' => 'api\modules\v1\userbackend\Module',
                ],
            ],
        ],
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
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

        /* // Local
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            //'savePath' => sys_get_temp_dir()
        ],
        */
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '128.199.70.160',
            'port' => 6479,
            'database' => 0,
        ],
        'session' => [
            'name' => 'adv-frontend-ws-2019',
            'class' => 'yii\redis\Session',
            'cookieParams' => ['httpOnly' => true, 'lifetime' => 3600 * 4],
            'timeout' => 3600 * 4,
        ],


//        'response' => [
//            'format' => yii\web\Response::FORMAT_JSON,
//            'charset' => 'UTF-8',
//            'on beforeSend' => function ($event) {
//               /*
//                @date_default_timezone_set('Asia/Ho_Chi_Minh');
//                $_user_Identity = Yii::$app->user->getIdentity();
//                $_user_id = $_user_Identity->getId();
//                $_user_email = $_user_Identity['email'];
//                $_user_AuthKey = $_user_Identity->getAuthKey();
//                $_user_name = $_user_Identity['username'];
//
//                //----ToDo Need More Infor param
//                $_user_app = 'Weshop2019';
//                $_user_request_suorce = "WEB_API_FRONTEND";//"APP/WEB_API_FRONTEND/WB_API_BACK_END"
//                $_request_ip = Yii::$app->getRequest()->getUserIP()
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
            'enableAutoLogin' => false,
            'enableSession' => false,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'log' => [
            /*
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
            */
            'targets' => [
                [
                    'class' => 'common\wsTelegramChatPush\TelegramTarget',
                    'levels' => ['error'],
                    'botToken' => '808279157:AAEbCkWEhLjvybgm9uH3ce0JsvA0j5DPtl4', // bot token secret key
                    'chatId' => '-1001468209025', // chat id or channel username with @ like 12345 or @channel
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
