<?php

$i18n = require dirname(dirname(__DIR__)) . '/common/i18n/i18n.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
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
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'api' => [
            'class' => 'common\components\Api',
        ],
        'storeManager' => [
            'class' => 'common\components\StoreManager',
            'storeId' => 1
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        /*
        'as access' => [
            'class' => 'mdm\admin\classes\AccessControl',
            'allowActions' => [
                'site/*',
                'admin/*',
            ]
        ],
        */
        'i18n' => $i18n,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Asia/Ho_Chi_Minh',
            'timeFormat' => 'php:H:i:s',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i:s'
        ],
        'cart' => [
            'class' => common\components\cart\CartManager::className(),
            'storage' => common\components\cart\storage\MongodbCartStorage::className()
        ],
        'wsLog' => [
            'class' => 'common\components\log\Logging'
        ],
        'wsFcnApn' => [
                  'class' => 'common\components\fcm\Notification',
                   //'param' => [  ],
        ],
        'productManager' => [
            'class' => 'common\products\ProductManager'
        ],
        'exRate' => [
            'class' => 'common\components\ExchangeRate'
        ],
        /*
        'cartws' => [
            'class' => 'common\components\book\cart\Cart',
            'storage' => [
                'class' => 'common\components\book\cart\storage\SessionStorage',
            ],
        ],
        */

        'queue' => [
            'class' => '\yii\queue\file\Queue',
            'as log' => '\yii\queue\LogBehavior',
            'path' => '@runtime/queue',
            'strictJobType' => false,
            'serializer' => '\yii\queue\serializers\JsonSerializer',
        ],
    ],
];
