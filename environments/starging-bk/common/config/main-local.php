<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=128.199.237.99;port=3306;dbname=weshop_global',
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
            'dsn' => 'mysql:host=128.199.237.99;port=3306;dbname=weshop_h',
            'username' => 'sys',
            'password' => 'FaUfevTz62pgY33JxxE',
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
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '128.199.70.160',
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
            'dsn' => 'mongodb://128.199.70.160:27017/admin',
        ],
        /*
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
        */
        'ga' => [
            'class' => 'baibaratsky\yii\google\analytics\MeasurementProtocol',
            'trackingId' => 'UA-140658371-1', // Put your real tracking ID here

            // These parameters are optional:
            'useSsl' => true, // If you’d like to use a secure connection to Google servers
            'overrideIp' => false, // By default, IP is overridden by the user’s one, but you can disable this
            'anonymizeIp' => true, // If you want to anonymize the sender’s IP address
            'asyncMode' => true, // Enables the asynchronous mode (see below)
            'autoSetClientId' => true, // Try to set ClientId automatically from the “_ga” cookie (disabled by default)
        ],
        /*
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
        */
    ],
];
