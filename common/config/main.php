<?php

$i18n = require dirname(dirname(__DIR__)) . '/common/i18n/i18n.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'common\components\FileCache',
            'noCacheParam' => 'noCache',
            'noCacheValidateKey' => 'yes'
        ],
        'api' => [
            'class' => 'common\components\Api',
        ],
        'storeManager' => [
            'class' => 'common\components\StoreManager'
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'i18n' => $i18n,
        'cart' => [
            'class' => common\components\cart\CartManager::className(),
            'storage' => common\components\cart\storage\SessionCartStorage::className()
        ]
    ],
];
