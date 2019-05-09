<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        //'ipFilter',
        'admin',
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'site/index',
    'modules' => [
        'payment' => [
            'class' => 'weshop\payment\PaymentModule',
        ],
        'ipFilter' => [
            'class' => 'johnsnook\ipFilter\Module',
            'mapquestKey' => 'tCUKiFTyWL5TH9MuiveoJmGdJumWp5Pt',
            'ipInfoKey' => 'fcbd97665ba41c',
            'proxyCheckKey' => '295474-6613h7-208634-w34448',
            'blowOff' => 'site/nope',
            'ignorables' => [
                'acontroller' => ['ignore-me', 'ignore-that','avast-Antivirus'],
                'whitelist' => ['127.0.0.1', '24.99.155.86','10.5.11.83']
            ]
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'menus' => [
                'assignment' => [
                    'label' => 'Grant Access' // change label
                ],
                'route' => null, // disable menu
            ],
        ],
    ],
    'components' => [
        /*
        'cart' => [
            'class' => 'backend\components\ShoppingCart',
            'sessionKey' => 'primary-cart',
        ],*/
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '745561014114-4emhnbr67v62flr2qbmfarho216cogc0.apps.googleusercontent.com',
                    'clientSecret' => 'WCSvO39GF8OppvoTqLp3Ilg3',
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
            'csrfParam' => '_csrf-frontend',
        ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-frontend',
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
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                // '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',

                /*****ipFilter******/
                'visitor' => '/ipFilter/visitor/index',
                'visitor/index' => '/ipFilter/visitor/index',
                'visitor/blowoff' => '/ipFilter/visitor/blowoff',
                'visitor/<id>' => 'ipFilter/visitor/view',
                'visitor/update/<id>' => 'ipFilter/visitor/update',
            ],
        ],
    ],
    'params' => $params,
];
