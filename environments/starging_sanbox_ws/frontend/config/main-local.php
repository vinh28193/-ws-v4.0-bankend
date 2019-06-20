<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '128.199.70.160',
            'port' => 6479,
            'database' => 0
        ],
        /*
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://192.168.11.252:27017/admin',
        ],
        */
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
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
