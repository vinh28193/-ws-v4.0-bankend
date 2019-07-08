<?php

$i18n = require dirname(dirname(__DIR__)) . '/common/i18n/i18n.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'sourceLanguage' => 'id-ID',
    'components' => [
        /*
        'cache' => [
            'class' => 'common\components\FileCache',
            'noCacheParam' => 'noCache',
            'noCacheValidateKey' => 'yes'
        ],

        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        */
        /*
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => '128.199.70.160',
                'port' => 6479,
                'database' => 0,
            ]
        ],
        */
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' =>  'cacheRedisWs',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'api' => [
            'class' => 'common\components\Api',
        ],
        'storeManager' => [
            'class' => 'common\components\StoreManager',
            'storeId' => 7
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
            'class' => 'common\products\ProductManager',
            'gates' => [
                'ebay' => [
                    'class' => 'common\products\ebay\EbayGate',
                    'baseUrl' => 'http://sv3.weshop.asia/ebay', // 'baseUrl' => 'https://api-lbc.weshop.asia/v3', //'https://ebay-api-wshopx-v3.weshop.com.vn/v3',
                    'searchUrl' => 'search',
                    'lookupUrl' => 'product'
                ],
                'amazon' => [
                    'class' => 'common\products\amazon\AmazonGate',
                    'baseUrl' => 'http://amazonapiv2.weshop.asia/amazon',
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
            'trackingId' => 'UA-140658371-1', // Put your real tracking ID here

            // These parameters are optional:
            'useSsl' => true, // If you’d like to use a secure connection to Google servers
            'overrideIp' => false, // By default, IP is overridden by the user’s one, but you can disable this
            'anonymizeIp' => true, // If you want to anonymize the sender’s IP address
            'asyncMode' => true, // Enables the asynchronous mode (see below)
            'autoSetClientId' => true, // Try to set ClientId automatically from the “_ga” cookie (disabled by default)
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
