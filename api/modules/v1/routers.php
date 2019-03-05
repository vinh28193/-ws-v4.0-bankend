<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 14:07
 */

return [
    [
        'class' => \yii\rest\UrlRule::className(),
        'controller' => ['v1/order'],
        'patterns' => [
            'PUT,PATCH v1/order/{id}' => 'v1\order\update',
            'DELETE v1/order/{id}' => 'v1\order\delete',
            'GET,HEAD v1/order/{id}' => 'v1\order\view',
            'POST v1/order' => 'create',
            'GET,HEAD v1/order' => 'index',
            'v1/order/{id}' => 'options',
            'v1/order' => 'options',
        ]
    ]
];