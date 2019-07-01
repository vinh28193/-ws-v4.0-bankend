<?php
return [
    'components' => [
        'storeManager' => [
            'storeId' => 7
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
            'dsn' => 'mysql:host=boxmesql;port=3306;dbname=ws_cms_indo',
            'username' => 'cms_indo',
            'password' => 'hg4UErPpPch52Xz3',
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
                'username' => 'dev.weshop@gmail.com',
                'password' => 'Aa@12345',
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
        'ga' => [
            'class' => 'baibaratsky\yii\google\analytics\MeasurementProtocol',
            'trackingId' => 'UA-53391706-1', // 'UA-140658371-1', // Put your real tracking ID here WS_ID : UA-53391706-1

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
