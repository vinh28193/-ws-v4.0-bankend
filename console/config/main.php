<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'namespace' => 'common\fixtures',
            'templatePath' => '@common/fixtures/templates',
            'fixtureDataPath' => '@common/fixtures/data'
        ],
        'migrate' => [
            'class' => 'common\components\consoles\controllers\MigrateController',
            'migrationPath' => ['@console/migrations']
        ],
        'mongodb-migrate' => [
            'class' => 'yii\mongodb\console\controllers\MigrateController',
            'migrationPath' => ['@console/mongodb-migrations']
        ],
        'rbac' => [
            'class' => 'common\rbac\controllers\RbacController',
        ]
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'storeManager' => [
            'class' => common\components\consoles\StoreManager::className(),
            'defaultDomain' => 'weshop.v4.api.frontend'
        ],
<<<<<<< HEAD
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ]
=======
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://192.168.11.252:27017/admin',
        ],
>>>>>>> c12c80a33ff1d5aa8e2861720016d45238f52247
    ],
    'params' => $params,
];
