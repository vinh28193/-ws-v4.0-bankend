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
        'storeManager',
        'language' => [
            'class' => 'common\bootstrap\AutoDetectLanguageBootstrapping'
        ],
        'log', 'queue'],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'cms/home',
    'components' => [
        /*
        'queue' => [
            'class' => '\yii\queue\file\Queue',
            'as log' => '\yii\queue\LogBehavior',
            'path' => '@runtime/queue',
        ],
        */
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                ],

            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '359287477925-tv9dqbpja9m218v4laaadl6h9ehqqj6e.apps.googleusercontent.com',
                    'clientSecret' => 'ambyLUowISOHJguxtMxI8y8D',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '450961485732787',
                    'clientSecret' => '212b51f106650cd45b1dc77a5a4d4850',
                ],
                /*
                'wallet' => [
                    'class' => 'frontend\modules\payment\providers\wallet\WalletClient',
                    'clientId' => 'testclient',
                    'clientSecret' => 'testpass',
                    'authUrl' => 'http://wallet.weshop-v4.local.vn/v1/rest/authorize',
                    'tokenUrl' => 'http://wallet.weshop-v4.local.vn/v1/rest/token',
                    'apiBaseUrl' => 'http://wallet.weshop-v4.local.vn/v1'
                ]
                */

            ],
        ],
        'request' => [
            'csrfParam' => 'csrf_frontend_ws2019',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login.html',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'session' => [
            'name' => 'adv-frontend-ws-2019',
            'class' => 'yii\redis\Session',
            'cookieParams' => ['httpOnly' => true, 'lifetime' => 3600 * 4],
            'timeout' => 3600 * 4,
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
            'errorAction' => 'error',
        ],
        'urlManager' => require(__DIR__ . '/urlManager.php'),
        'ecomobi' => [
            'class' => 'common\components\EcomobiComponent',
            'baseApiUrl' => 'http://cps.mtrackings.mobi',
//            'baseApiUrl' => 'http://dev-cps.mtrackings.mobi', // DEV MODE
            'token' => '7c6298ea7156237e67e0c8c3146ac446', // VN
//            'token' => '21c4633328519d0802a0f3d5f4de8647', // ID
//            'token' => '62b5c2a506ccfa164d05f4987e810b6e', //MY
            'offerId' => 'weshop',
            'cookieName' => '_____ecomobiCookiePrefix'

        ]

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
        ],
        'payment' => [
            'class' => 'frontend\modules\payment\Module',
        ],
        'account' => [
            'class' => 'frontend\modules\account\Module',
        ],
        'favorites' => [
            'class' => 'frontend\modules\favorites\Module',
        ],
        'landing' => [
            'class' => 'landing\LandingModule',
        ],
        'debug' => [
            'class' => '\yii\debug\Module',
            'panels' => [
                'queue' => '\yii\queue\debug\Panel',
            ],
        ],
    ],
];
