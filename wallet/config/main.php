<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'sourceLanguage' => 'en-US',
    'language' => 'vi',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'wallet\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'wallet\modules\v1\Module',
            'options' => [
                'token_param_name' => 'accessToken',
                'access_lifetime' => 3600,
                'allow_implicit' => true,
            ],
            'storageMap' => [
                'user_credentials' => 'wallet\modules\v1\models\WalletClient'
            ],
            'grantTypes' => [
                'client_credentials' => [
                    'class' => 'OAuth2\GrantType\ClientCredentials',
                    'allow_public_clients' => false
                ],
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials'
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ],
            ]
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wsQkuZ_6i8hHBarTyVfpgblRBYLGcKtM',
        ],
        'user' => [
            'identityClass' => 'wallet\modules\v1\models\WalletClient',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-wallet', 'httpOnly' => true],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'dev.weshopasia@gmail.com',
                'password' => 'weshopdev@123',
                'port' => '587',
                'encryption' => 'tls'
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-wallet',
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
            'errorAction' => 'error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Asia/Ho_Chi_Minh',
            'timeFormat' => 'php:H:i:s',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i:s'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'v1/'
            ],
        ]
    ],
    'params' => $params,
];
