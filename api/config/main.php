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
    'bootstrap' => ['log'], //,'v1/payment'
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
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        /*
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            // ...
        ],
        */
        'response' => [
            //'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $_data = $response->data;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'timestamp' => time(),
                        'path' => Yii::$app->request->getPathInfo(),
                        'data' => $response->data,
                    ];
                    /** Todo Save mongodb to Report API route **/

                    $_rest_data = [ "RestApiCall" => [
                        "success" => $response->isSuccessful,
                        "timestamp" => time(),
                        "path" => @json_encode(Yii::$app->request->getPathInfo()),
                        "data" => @json_encode($_data),
                        "date"=>date('Y-m-d H:i:s'),
                    ]];

                    /*
                    $_rest_data =  [ "RestApiCall" => [
                        "success" => true,
                        "timestamp" => 1551682356,
                        "path" => '1/order',
                        "data" =>'',
                        'date'=>date('Y-m-d H:i:s'),
                        ]];
                    */

                    $rest_model = new RestApiCall();
                    if ($rest_model->load($_rest_data) && $rest_model->save()) {
                        $id = (string)$rest_model->_id;
                    } else {}

                    /*
                    $po = ["Logrouteapi" => [ "name" => "Hoang Anh Baby Move" , "email" => "babyhoanganh19872019@gmail.com" , "address" => "Ha noi" , "status" => "2" ]];
                    $model = new Logrouteapi();
                    if ($model->load($po) && $model->save()) {
                        $id = (string)$model->_id;
                        //var_dump($id); die("909099");
                    } else {}
                    */
                }
            },
        ],

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
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '1/register'=>'site/register',
                '1/authorize'=>'site/authorize',
                '1/accesstoken'=>'site/accesstoken',
                '1/me'=>'site/me',
                '1/logout'=>'site/logout',

               ### employees
                '1/employees'=>'employee/index',
                '1/employees/view/<id>'=>'employee/view',
                '1/employees/create'=>'employee/create',
                '1/employees/update/<id>'=>'employee/update',
                '1/employees/delete/<id>'=>'employee/delete',

                ### Post
                '1/post'=>'post/index',
                '1/post/view/<id>'=>'post/view',
                '1/post/create'=>'post/create',
                '1/post/update/<id>'=>'post/update',
                '1/post/delete/<id>'=>'post/delete',

                ### Order 27/02/2019
                '1/order'=>'order/index',
                '1/order/view/<id>'=>'order/view',
                '1/order/create'=>'order/create',
                '1/order/update/<id>'=>'order/update',
                '1/order/delete/<id>'=>'order/delete',

                ### Login api V1
                'v1/<name>/<controller:\w+>/<action:\w+>'=>'v1/<controller>/<action>',
                'v1/<name>/api/<controller:\w+>/<action:\w+>/<actionKey:\w*>'=>'v1/api/<controller>/<action>',


                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                // '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',

            ],

        ],
    ],
    'params' => $params,
];
