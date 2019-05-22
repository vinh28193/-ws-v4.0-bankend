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
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
            'templatePath' => '@common/fixtures/templates',
            //'fixtureDataPath' => '@common/fixtures/data/units' // units , models , data_fixed , components
            'fixtureDataPath' => '@common/fixtures/data/data_fixed'
        ],
        'migrate' => [
            'class' => 'common\components\consoles\controllers\MigrateController',
            'migrationPath' => ['@console/migrations'],
        ],
        'mongodb-migrate' => [
            'class' => 'yii\mongodb\console\controllers\MigrateController',
            'migrationPath' => ['@console/mongodb-migrations']
        ],
        'i18n-migrate' => [
            'class' => 'common\components\consoles\controllers\MigrateController',
            'migrationPath' => ['@yii/i18n/migrations']
        ],
        'rbac' => [
            'class' => 'common\rbac\controllers\RbacController',
        ]
    ],
    'components' => [
        'queue' => [
            'class' => '\yii\queue\file\Queue',
            'as log' => '\yii\queue\LogBehavior',
            'path' => '@runtime/queue',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/admin',
        ],
    ],
    'params' => $params,
];
