<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 16:01
 */
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '1/register'=>'site/register',
        '1/authorize'=>'site/authorize',
        '1/access-token'=>'site/access-token',
        '1/me'=>'site/me',
        '1/logout'=>'site/logout',

        'callback/update-item' => 'callback/update-item',

        'test/get-custom-fee'=>'test/get-custom-fee',
        'test/callback-boxme'=>'test/callback-boxme',

        '1/addon/get'=>'addon/index',
        '1/addon/order'=>'addon/order-list',
        '1/addon/view'=>'addon/view',

        ### employees
        '1/employees'=>'employee/index',
        '1/employees/view/<id>'=>'employee/view',
        '1/employees/create'=>'employee/create',
        '1/employees/update/<id>'=>'employee/update',
        '1/employees/delete/<id>'=>'employee/delete',

        ### Post
        '1/post'=>'post/index',
        '1/post/view/<id>'=>'post/view',
        '1/post/create'=>'post/create',
        '1/post/update/<id>'=>'post/update',
        '1/post/delete/<id>'=>'post/delete',

        ### Order 27/02/2019
        '1/order'=>'order/index',
        '1/order/view/<id>'=>'order/view',
        '1/order/create'=>'order/create',
        '1/order/update/<id>'=>'order/update',
        '1/order/delete/<id>'=>'order/delete',


        ### Product 27/02/2019
        '1/product'=>'product/index',
        '1/product/view/<id>'=>'product/view',
        '1/product/create'=>'product/create',
        '1/product/update/<id>'=>'product/update',
        '1/product/delete/<id>'=>'product/delete',


        ### TestService Call Details EBAY + AMAZON  27/02/2019 + Data Fixed
        '1/service-e-a/get-amazon'=>'service/get-amazon',
        '1/service-e-a/get-amazon-detail'=>'service/get-amazon-detail',
        '1/service-e-a/ebay-detail'=>'service/ebay-detail',
        '1/service-e-a/create-order-product'=>'service/create-order-product',


        ### API SOCIAL 05/03/2019
        '1/api/social'=>'api-social/index',
        '1/api/social/convert-token'=>'api-social/convert-token',

        ### Login api V1
        'v1/<name>/<controller:\w+>/<action:\w+>'=>'v1/<controller>/<action>',
        'v1/<name>/api/<controller:\w+>/<action:\w+>'=>'v1/api/<controller>/<action>',

        //Manifest
        'v1/<name>/api/manifest/update/<id:\d+>'=>'v1/api/manifest/update',
        'v1/<name>/api/manifest/view/<id:\d+>'=>'v1/api/manifest/view',
        'v1/<name>/api/manifest/index/<limit:\d+>-<page:\d+>.html'=>'v1/api/manifest/index',
        'v1/<name>/api/manifest/index'=>'v1/api/manifest/index',
        'v1/<name>/api/manifest/delete/<id:\d+>'=>'v1/api/manifest/delete',
        'v1/<name>/api/manifest/delete/<id:\d+>/<is_remove_package:\w*>'=>'v1/api/manifest/delete',

        //Shipment
        'v1/<name>/api/shipment/update/<id:\d+>'=>'v1/api/shipment/update',
        'v1/<name>/api/shipment/view/<id:\d+>'=>'v1/api/shipment/view',
        'v1/<name>/api/shipment/index/<limit:\d+>-<page:\d+>.html'=>'v1/api/shipment/index',
        'v1/<name>/api/shipment/index'=>'v1/api/shipment/index',
        'v1/<name>/api/shipment/delete/<id:\d+>'=>'v1/api/shipment/delete',
        'v1/<name>/api/shipment/delete/<id:\d+>/<is_leave:\w*>'=>'v1/api/shipment/delete',

        '<controller:\w+>/<id:\d+>' => '<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
        '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
        // '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        'test/<action:\w+>/<id:\d+>' => 'tester/<action>',
    ],

];
?>
