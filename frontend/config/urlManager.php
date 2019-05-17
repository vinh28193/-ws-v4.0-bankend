<?php
/**** Edit 19/09/2017*****/

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'suffix' => '',
    'rules' => [
        // home
        '' => 'cms/home/index',
        'index.php' => 'cms/home/index',
        'index.html' => 'cms/home/index',
        'search/<keyword:[^/]+>.html' => 'cms/search/index',
        'description/<type:[^/]+>-<id:[^/]+>.html' => 'cms/description/index',

        // Message read
        'GET 404.html' => 'common',

        // ebay
        'ebay.html' => 'cms/ebay/index',
        'GET ebay/item/<name:[0-9A-Za-z_-]*>-<id:[0-9_-]*>.html' => 'ebay/item/detail',
        'ebay/search/<keyword:[^/]+>.html' => 'ebay/search/index',
        'ebay/seller/<seller:[^/]+>.html' => 'ebay/search/index',
        'ebay/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'ebay/search/index',
        'GET ebay/categories.html' => 'ebay/ebay/categories',

        // amazon
        'amazon.html' => 'cms/amazon/index',
        'GET amazon/item/<name:[0-9A-Za-z_-]*>-<id:[0-9A-Za-z_-]+>.html' => 'amazon/item/detail',
        'amazon/search/<keyword:[^/]+>.html' => 'amazon/search/index',
        'amazon/seller/<seller:[^/]+>.html' => 'amazon/search/index',
        'amazon/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'amazon/search/index',
        'GET amazon/categories.html' => 'amazon/amazon/categories',

        // amazon-jp
        'amazon-jp.html' => 'cms/amazon-jp/index',
        'GET amazon-jp/item/<name:[0-9A-Za-z_-]*>-<id:[0-9A-Za-z_-]+>.html' => 'amazon-jp/item/detail',
        'amazon-jp/search/<keyword:[^/]+>.html' => 'amazon-jp/search/index',
        'amazon-jp/seller/<seller:[^/]+>.html' => 'amazon-jp/search/index',
        'amazon-jp/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'amazon-jp/search/index',
        'GET amazon-jp/categories.html' => 'amazon-jp/amazon-jp/categories',

        // cart
        'GET my-cart.html' => 'checkout/cart',

        // checkout
        'GET checkout-<type:[0-9A-Za-z_-]*>.html' => 'checkout/shipping',
        'checkout/login.html' => 'checkout/shipping/login',
        'checkout/signup.html' => 'checkout/shipping/signup',

        //account
        'login.html' => 'secure/login',
        'signup.html' => 'secure/register',
        'logout.html' => 'secure/logout',
        'GET account/order/<id:[0-9A-Za-z_-]*>.html' => 'account/order/view',
    ]
];
