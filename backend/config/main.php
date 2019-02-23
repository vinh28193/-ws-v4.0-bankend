<?php
use backend\cart\storage\SessionStorage;
Yii::$container->setSingleton('backend\cart\ShoppingCart');
Yii::$container->set('backend\cart\storage\StorageInterface', function() {
    return new SessionStorage(Yii::$app->session, 'primary-cart');
});

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'site/index',
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\v1\Module',
            'modules' => [
                'backend' => [
                    'class' => 'backend\modules\v1\backend\Module',
                ],
                'weshop' => [
                    'class' => 'backend\modules\v1\weshop\Module',
                    'modules' => [
                        'customer' => [
                            'class' => 'backend\modules\v1\weshop\customer\Module',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
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

                /***Post API GET NOT POST , PUT , UPDATE ***/
                'class' => 'yii\rest\UrlRule', 'controller' => 'api/posts'
            ],
        ],
    ],
    'params' => $params,
];
