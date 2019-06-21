<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=boxmesql;port=3306;dbname=weshop_global',
            'username' => 'sys',
            'password' => 'FaUfevTz62pgY33JxxE',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache',
            //'tablePrefix' => 'WS_',
        ],
        'db_cms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=boxmesql;port=3306;dbname=weshop_h',
            'username' => 'sys',
            'password' => 'FaUfevTz62pgY33JxxE',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
        ],
        /*
        'cache' => [
            'class' => 'common\components\FileCache',
            'noCacheParam' => 'noCache',
            'noCacheValidateKey' => 'yes'
        ],
        */
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'sessionRedisBm',
                'port' => 6479,
                'database' => 0,
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'sessionRedisBm',
            'port' => 6479,
            'database' => 0,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'no-reply-dev-wsvn@weshop.com.vn',
                'password' => 'a>d2&XK4@nowWeshop2019!@#',
                'port' =>  '587',
                'encryption' => 'tls'
            ],
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://MongoBM:27017/admin',
        ],
        'db_oauth'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=3306;dbname=weshop_oauth',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
        ],
        'ga' => [
            'trackingId' => 'UA-140658371-1',
        ],
        'productManager' => [
            'gates' => [
                'ebay' => [
                    'baseUrl' => 'https://api-lbc.weshop.asia/v3', //'https://ebay-api-wshopx-v3.weshop.com.vn/v3',
                ],
                'amazon' => [
                    'baseUrl' => 'http://amazonapiv2.weshop.asia/amazon', // 'http://157.230.175.213:8000', //
                ],
                'amazon-jp' => [
                    'baseUrl' =>  'http://amazonapiv2.weshop.asia/amazon', // 'http://157.230.175.213:8000', //
                ]
            ]
        ],
    ],
];
