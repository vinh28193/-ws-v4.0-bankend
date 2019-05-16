<?php
use userbackend\cart\storage\SessionStorage;
Yii::$container->setSingleton('userbackend\cart\ShoppingCart');
Yii::$container->set('userbackend\cart\storage\StorageInterface', function() {
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
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'controllerNamespace' => 'userbackend\controllers',
    'defaultRoute' => 'site/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
//            'identityClass' => 'common\models\User',
            'identityClass' => 'common\models\Customer',

            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'domain'=>'.yii2-members-system.dev', 'path'=>'/'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the userbackend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'common\wsTelegramChatPush\TelegramTarget',
                    'levels' => ['error'],
                    'botToken' => '685361653:AAEQWxQ2lHdvrjEElZfgIaiBRxJTvOCHp1A', // bot token secret key
                    'chatId' => '855666866', // chat id or channel username with @ like 12345 or @channel
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
//                '<controller:\w+>/<code:\w+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<code:\w+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
