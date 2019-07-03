<?php
return [
    'components' => [
        'storeManager' => [
            'storeId' => 1
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=boxmesql;port=3306;dbname=weshop_global',
            'username' => 'ws2020',
            'password' => 'q4CgJyTNBT63RWfQ',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache',
            //'tablePrefix' => 'WS_',
        ],
        'db_cms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=boxmesql;port=3306;dbname=weshop_h',
            'username' => 'ws2020',
            'password' => 'q4CgJyTNBT63RWfQ',
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
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'sessionRedisBm',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        */
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'cacheRedisWs',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'sessionRedisBm',
            'port' => 6379,
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
                'username' => 'noreplyws2019@gmail.com',
                'password' => 'uyhqwirrtdygyllp',
                'port' =>  '587',
                'encryption' => 'tls'
            ],
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://backup:QuKUBS1cQ2Q@mongodbWs:27017/admin',
        ],
//        'db_oauth'=>[
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=localhost;port=3306;dbname=weshop_oauth',
//            'username' => 'root',
//            'password' => '',
//            'charset' => 'utf8',
//            'enableSchemaCache' => false,
//            'schemaCacheDuration' => 7200,
//            'schemaCache' => 'cache'
//        ],

        'productManager' => [
            'class' => 'common\products\ProductManager',
            'gates' => [
                'ebay' => [
//                    'class' => 'common\products\ebay\EbayGate',
//                    'baseUrl' => 'https://api-lbc.weshop.asia/v3', //'https://ebay-api-wshopx-v3.weshop.com.vn/v3',
                    'class' => 'common\products\ebay\EbayGateV4',
                    'baseUrl' => 'http://s1.weshop.asia/ebay', // 'http://sv3.weshop.asia/ebay',
                    'searchUrl' => 'search',
                    'lookupUrl' => 'product'
                ],
                'amazon' => [
//                    'class' => 'common\products\amazon\AmazonGate',
//                    'baseUrl' => 'http://amazonapiv2.weshop.asia/amazon',
                    'class' => 'common\products\amazon\AmazonGateV3',
                    'baseUrl' =>'http://s1.weshop.asia/amazon', // 'http://sv1.weshop.asia/amazon',
                    'store' => \common\products\amazon\AmazonProduct::STORE_US
                ],
                'amazon-jp' => [
                    'class' => 'common\products\amazon\AmazonGate',
                    'baseUrl' => 'http://amazonapiv2.weshop.asia/amazon',
                    'store' => \common\products\amazon\AmazonProduct::STORE_JP
                ]
            ]
        ],
        'ga' => [
            'class' => 'baibaratsky\yii\google\analytics\MeasurementProtocol',
            'trackingId' => 'UA-68960158-1' , // 'UA-68960158-1', // Put your real tracking ID here WS-VN UA-68960158-1

            // These parameters are optional:
            'useSsl' => true, // If you’d like to use a secure connection to Google servers
            'overrideIp' => false, // By default, IP is overridden by the user’s one, but you can disable this
            'anonymizeIp' => true, // If you want to anonymize the sender’s IP address
            'asyncMode' => true, // Enables the asynchronous mode (see below)
            'autoSetClientId' => true, // Try to set ClientId automatically from the “_ga” cookie (disabled by default)
        ],
        /*
          'request' => [
                'enableCookieValidation' => false,
          ],
         */
    ],
];
