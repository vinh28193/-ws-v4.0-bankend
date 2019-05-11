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
    'bootstrap' => ['log', function($app){
        // dev mode bootstrap
        $class = Yii::$app->user->identityClass;
        Yii::$app->user->setIdentity($class::findOne(1));
    }],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\Customer',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                    'botToken' => '759337325:AAFSTmTF7eqaaly8MV1JSKP13vtVnBOL5Jc', // bot token secret key
                    'chatId' => '-1001205216479', // chat id or channel username with @ like 12345 or @channel
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => require(__DIR__ . '/urlManager.php'),

    ],
    'params' => $params,
    'modules' => [
        'ebay' => [
            'class' => 'frontend\modules\ebay\EbayModule',
        ],
        'amazon' => [
            'class' => 'frontend\modules\amazon\Module',
        ],
        'amazon-jp' => [
            'class' => 'frontend\modules\amazonJp\Module',
        ],
        'cms' => [
            'class' => 'frontend\modules\cms\Module',
        ],
        'cart' => [
            'class' => 'frontend\modules\cart\Module',
        ],
        'checkout' => [
            'class' => 'frontend\modules\checkout\Module',
        ]
    ],
];
