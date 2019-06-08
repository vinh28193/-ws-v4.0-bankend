<?php
return [
    'components' => [
        'db' => [
            'class' => 'common\components\db\Connection',
            'dsn' => 'oci:dbname=//178.128.60.187:1521/boxme', // Oracle
            'username' => 'weshopdev',
            'password' => '12345677',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache',
            'tablePrefix' => 'WS_',

        ],
        'db_cms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=128.199.237.99;port=3306;dbname=weshop_',
            'username' => 'weshop2020',
            'password' => 'FaUfevTz62pgY3JE',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => '128.199.70.160',
                'port' => 6479,
                'database' => 0,
            ]
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
            'dsn' => 'mongodb://128.199.70.160:27017/admin',
        ],
        'db_oauth'=>[
            /*
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=3306;dbname=weshop_oauth',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
            */
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
                    'baseUrl' => 'http://157.230.175.213:8000', // 'http://amazonapiv2.weshop.asia/amazon',
                ],
                'amazon-jp' => [
                    'baseUrl' => 'http://157.230.175.213:8000', // 'http://amazonapiv2.weshop.asia/amazon',
                ]
            ]
        ],
    ],
];
