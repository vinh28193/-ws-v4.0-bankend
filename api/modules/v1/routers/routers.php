<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 14:07
 */

return [
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['s' => 'secure'],
        'patterns' => [
            'GET me' => 'secure/me',
            'POST authorize' => 'secure/authorize',
            'POST access-token' => 'secure/access-token',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['check-out'],
        'patterns' => [
            'POST' => 'create',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => [ 'product'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>'
        ],
        'patterns' => [
            'GET,HEAD' => 'index',
            'PUT,PATCH {id}' => 'update',
            'DELETE {id}' => 'delete',
            'GET,HEAD {id}' => 'view',
            'POST' => 'create',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['order'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{image}' => '<image:\\d[\\d,]*>',
        ],
        'patterns' => [
            'GET,HEAD' => 'index',
            'PUT,PATCH {id}' => 'update',
            'DELETE {id}' => 'delete',
            'GET,HEAD {id}' => 'view',
            'POST' => 'create',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => [
            'PUT edit-image/<id:\d+>'=> 'edit-image',
            'OPTIONS edit-image/<id:\d+>'=> 'options',
            'PUT edit-variant/<id:\d+>'=> 'edit-variant',
            'OPTIONS edit-variant/<id:\d+>'=> 'options'
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['p' => 'package'],
        'patterns' => [
            'GET' => 'index',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['s' => 'shipment'],
        'patterns' => [
            'GET' => 'index',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['tracking-code'],
        'patterns' => [
            'GET' => 'index',
            'POST' => 'create',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['manifest'],
        'patterns' => [
            'GET' => 'index',
            'POST' => 'create',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['chat' =>'rest-api-chat'],
        'tokens' => [
            '{id}' => '<id:\\w[\\w,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
            'GET,HEAD' => 'index',
            'PUT,PATCH {id}' => 'update',
            'DELETE {id}' => 'delete',
            'GET,HEAD {id}' => 'view',
            'POST' => 'create',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'if_service' => true,
        'controller' => ['chat-service' =>'service/rest-service-chat'],
        'tokens' => [
            '{id}' => '<id:\\w[\\w,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
            'PUT {id}' => 'customer-viewed',
            'PATCH {id}' => 'group-viewed',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => []
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['purchase' => 'purchase'],
        'patterns' => [
            'GET,HEAD' => 'index',
            'PUT,PATCH {id}' => 'update',
            'DELETE {id}' => 'delete',
            'GET,HEAD {id}' => 'view',
            'POST' => 'create',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => [
            'PUT update/<id:\d+>'=> 'update',
            'OPTIONS update/<id:\d+>'=> 'options',
            'POST create/<id:\d+>'=> 'create',
            'OPTIONS create/<id:\d+>'=> 'options'
        ]
    ],
];
