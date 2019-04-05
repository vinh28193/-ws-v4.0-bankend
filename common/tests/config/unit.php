<?php

return yii\helpers\ArrayHelper::merge(
    require '../../config/main.php',
    require '../../config/main-local.php',
    [
        'id' => 'app-test',
        'components' => [
            'storeManager' => [
                'class' => 'common\components\StoreManager',
                'storeClass' => 'common\tests\libs\ActiveStore',
                'defaultDomain' => 'localhost:80'
            ],
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=weshop-global-test',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ],
        ]
    ]
);