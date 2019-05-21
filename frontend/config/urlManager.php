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
        'checkout/office/<code:[^/]+>/success.html' => 'checkout/notify/office-success',

        // payment
        'payment/process' => 'payment/payment/process',
        'payment/<merchant:[^/]+>/return.html' => 'payment/payment/return',
        'otp/<code:[^/]+>/verify.html' => 'payment/wallet/otp-verify',
        'otp/captcha' => 'payment/wallet/captcha',
        //account
        'my-weshop.html' => 'account/home',
        'login.html' => 'secure/login',
        'signup.html' => 'secure/register',
        'logout.html' => 'secure/logout',

        //wallet
        'my-weshop/wallet.html' => 'account/wallet/index',
        'my-weshop/order/<orderCode:[0-9A-Za-z_-]*>.html' => 'account/order/view',
        'my-weshop/wallet/transaction/<transaction_code:[^/]+>/detail.html' => 'account/wallet/detail',
        'my-wallet/topup.html' => 'account/api/wallet-service/topup',
        'my-wallet/topup/<merchant:[^/]+>/return.html' => 'account/api/wallet-service/return',
        'my-weshop/<controller>/<action>.html' => 'account/<controller>/<action>',
        'my-weshop/api/<controller>/<action>.html' => 'account/api/<controller>/<action>',

    ]
];
