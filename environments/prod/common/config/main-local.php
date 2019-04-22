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
        ],
    ],
];
