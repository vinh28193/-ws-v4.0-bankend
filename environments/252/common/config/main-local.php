<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.11.252;port=3306;dbname=weshop-global-dev',
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
            'useFileTransport' => true,
        ],
    ],
];
