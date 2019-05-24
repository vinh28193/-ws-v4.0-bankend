<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=weshop-global-prod',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'db_cms' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.11.252;port=3306;dbname=weshop_cms',
            'username' => 'dev',
            'password' => 'Mwqs]Avk>q+8N2vs)zV36ia',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
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
                'username' => 'no-reply-wsvn@weshop.com.vn',
                'password' => 'n*3NGa9&@WS2019now',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://192.168.11.252:27017/admin',
        ],
        'db_oauth'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=3306;dbname=weshop_oauth',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 7200,
            'schemaCache' => 'cache'
        ],
        'ga' => [
            'class' => 'baibaratsky\yii\google\analytics\MeasurementProtocol',
            'trackingId' => 'UA-XXXX-Y', // Put your real tracking ID here

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
