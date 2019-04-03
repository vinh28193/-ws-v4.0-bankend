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
        'controller' => ['data'],
        'patterns' => [
            'POST' => 'create',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['product'],
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
            'PUT edit-image/<id:\d+>' => 'edit-image',
            'OPTIONS edit-image/<id:\d+>' => 'options',
            'PUT edit-variant/<id:\d+>' => 'edit-variant',
            'OPTIONS edit-variant/<id:\d+>' => 'options',
            'GET assign/<id:\d+>' => 'sale-assign',
            'OPTIONS assign/<id:\d+>' => 'options',
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['p' => 'package', 's' => 'shipment', 'tracking-code','manifest','tracking'],
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
        'controller' => ['chat' => 'rest-api-chat'],
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
        //'if_service' => true,
        'controller' => ['chat-service' => 'service/rest-service-chat'],
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
        'controller' => ['purchase'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
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
        'extraPatterns' => [
            'PUT update/<id:\d+>'=> 'update',
            'OPTIONS update/<id:\d+>'=> 'options',
            'POST create'=> 'create',
            'OPTIONS create'=> 'options',
            'DELETE delete/<id:\d+>'=> 'delete',
            'OPTIONS delete/<id:\d+>'=> 'options',
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['service-purchase' => 'service/purchase-service'],
        'patterns' => [
            'GET' => 'get-list-card-payment,get-list-account',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => [
            'GET get-list-card-payment'=> 'get-list-card-payment',
            'OPTIONS get-list-card-payment'=> 'options',
            'GET get-list-account'=> 'get-list-account',
            'OPTIONS get-list-account'=> 'options'
        ]
    ],

    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['purchase-account' => 'service/purchase-service'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
            'GET' => 'list-account',
        ],
        'extraPatterns' => [
            'GET list-account' => 'list-account',
            'OPTIONS list-account' => 'options',
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['card-payment' => 'service/rest-service-list-card-payment'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
           // 'GET' => 'list-card-payment',
            'POST' => 'list-card-payment',
            'OPTIONS' => 'options',
        ],
        'extraPatterns' => [ ]
    ],

    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['system-location' => 'system-state-province'],
        'patterns' => [
            'GET,POST' => 'index',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['sale-support' => 'sale'],
        'patterns' => [
            'GET,POST' => 'index',
            'PUT,POST {id}' => 'assign',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['seller' => 'seller'],
        'patterns' => [
            'GET,POST' => 'index',
            'OPTIONS' => 'options',
        ],
    ],

    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['actionlog' => 'rest-action-log'],
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
        'controller' => ['paymentlog' => 'rest-payment-log-w-s'],
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
        'controller' => ['walletlog' => 'rest-wallet-log-ws'],
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
        'controller' => ['boxme-inspect-log' => 'rest-bm-log-inspect-ws'],
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
        'controller' => ['send-shipment-return-log' => 'rest-bm-log-send-return-shipment-ws'],
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
        'controller' => ['send-shipment-log' => 'rest-boxme-log-send-shipment-ws'],
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
        'controller' => ['package-item' => 'package-item'],
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
        'controller' => ['fee' => 'product-fee'],
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
        'extraPatterns' => [
            'PUT update/<id:\d+>'=> 'update',
            'OPTIONS update/<id:\d+>'=> 'options',
            'GET view/<id:\d+>'=> 'view',
            'OPTIONS view/<id:\d+>'=> 'options',
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['gate' => 'product-gate'],
        'patterns' => [
            'GET,POST search' => 'search',
            'GET,POST get' => 'detail',
            'GET,POST calc' => 'calculator',
            'OPTIONS {id}' => 'options',
            'OPTIONS' => 'options',
        ],
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['coupon' => 'coupon'],
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
        'controller' => ['policy' => 'category-customer-policy'],
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
        'controller' => ['ext' => 'service/extension'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
            'POST' => 'update',
        ],
        'extraPatterns' => [
            'POST update' => 'update',
        ]
    ],
    [
        'class' => \common\filters\ApiUrlRule::className(),
        'prefix' => 'v1',
        'pluralize' => false,
        'controller' => ['detail-boxme' => 'service/manifest-box-me'],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{token}' => '<token:\\d[\\d,]*>',
        ],
        'patterns' => [
            'GET' => 'get-detail',
        ],
        'extraPatterns' => [
            'GET get {manifest_id}' => 'get-detail',
        ]
    ],
];
