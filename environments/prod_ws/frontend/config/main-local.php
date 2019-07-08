<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'sessionRedisBm',
            'port' => 6379,
            'database' => 0
        ],
        'log' => [
            // Query QA IO
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'notamedia\sentry\SentryTarget',
                    'dsn' => 'https://c45d3a0d75be4d4d981cab62b26a9748@sentry.io/1498360',
                    'levels' => ['error', 'warning'],
                    'context' => true // Write the context information. The default is true.
                ],
            ],
        ],
        /*
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://192.168.11.252:27017/admin',
        ],
        */
    ],
];

if (YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1','*'],
        'panels' => [
            'mongodb' => [
                'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
            ],
            'httpclient' => [
                'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'ns' => 'common\models\db',
                'useTablePrefix' => true,
                'baseClass' => 'common\components\db\ActiveRecord',
                'generateQuery' => false,
                'queryNs' => 'common\models\queries',
                'queryBaseClass' => 'common\components\db\ActiveQuery',
                'enableI18N' => true,
                'messageCategory' => 'db'
            ],
            'model_cms' => [
                'class' => 'common\gii\generators\cms\Generator',
            ],
            'mongoDbModel' => [
                'class' => 'yii\mongodb\gii\model\Generator'
            ]
        ]
    ];
}

return $config;
