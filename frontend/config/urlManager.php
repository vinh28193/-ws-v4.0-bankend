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
        'search/set-default' => 'cms/search/set-default',
        'description/<type:[^/]+>-<id:[^/]+>.html' => 'cms/description/index',

        // Message read
        'GET 404.html' => 'common',

        // ebay
        'ebay.html' => 'cms/ebay/index',
        'GET ebay/item/<name:[0-9A-Za-z_-]*>-<id:[0-9_-]*>.html' => 'ebay/item/detail',
        'POST ebay/item/detail' => 'ebay/item/detail',
        'ebay/search/<keyword:[^/]+>.html' => 'ebay/search/index',
        'ebay/search/ebay/search/<keyword:[^/]+>.html' => 'ebay/search/index',
        'ebay/seller/<seller:[^/]+>.html' => 'ebay/search/index',
        'ebay/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'ebay/search/index',
        'GET ebay/categories.html' => 'ebay/ebay/categories',
        'ebay/service/browse/<keyword:[^/]+>' => 'ebay/search/index',


        // amazon
        'amazon.html' => 'cms/amazon/index',
        'GET amazon/item/<name:[0-9A-Za-z_-]*>-<id:[0-9A-Za-z_-]+>.html' => 'amazon/item/detail',
        'GET amazon/item/get-offer/<id:[0-9A-Za-z_-]+>' => 'amazon/item/get-offer',
        'amazon/search/<keyword:[^/]+>.html' => 'amazon/search/index',
        'amazon/seller/<seller:[^/]+>.html' => 'amazon/search/index',
        'amazon/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'amazon/search/index',
//        'amazon/category/<name:[0-9a-zA-Z_-]+>-<node:\d+>.html' => 'amazon/category/index',
        'GET amazon/categories.html' => 'amazon/amazon/categories',

        // amazon-jp
        'amazon-jp.html' => 'cms/amazon-jp/index',
        'GET amazon-jp/item/<name:[0-9A-Za-z_-]*>-<id:[0-9A-Za-z_-]+>.html' => 'amazon-jp/item/detail',
        'amazon-jp/search/<keyword:[^/]+>.html' => 'amazon-jp/search/index',
        'amazon-jp/seller/<seller:[^/]+>.html' => 'amazon-jp/search/index',
        'amazon-jp/category/<name:[0-9a-zA-Z_-]+>-<category:\d+>.html' => 'amazon-jp/search/index',
        'GET amazon-jp/categories.html' => 'amazon-jp/amazon-jp/categories',

        // landing
        'GET landing-page/<name:[0-9a-zA-Z_-]+>-<id:\d+>.html' => 'landing/page/index',
        'GET landing-request/<name:[0-9a-zA-Z_-]+>-<id:\d+>.html' => 'landing/request/index',

        // cart
        'GET my-cart.html' => 'checkout/cart',

        // checkout

        'GET checkout.html' => 'checkout/shipping',
        'checkout/login.html' => 'checkout/shipping/login',
        'checkout/signup.html' => 'checkout/shipping/signup',
        'checkout/office/<code:[^/]+>/success.html' => 'checkout/notify/office-success',
        'checkout/bank-transfer/<code:[^/]+>/success.html' => 'checkout/notify/bank-transfer-success',
        'checkout/nice-pay/<code:[^/]+>/success.html' => 'checkout/notify/nice-pay-success',
        'checkout/invoice/<code:[^/]+>/success.html' => 'checkout/billing/success',
        'checkout/invoice/<code:[^/]+>/fail.html' => 'checkout/billing/fail',
        'order-<code:[^/]+>/bill.html' => 'checkout/billing/index',

        // payment
        'payment/nicepay/return.html' => 'payment/payment/return-nicepay',
        'payment/process' => 'payment/payment/process',
        'payment/<merchant:[^/]+>/return.html' => 'payment/payment/return',
        'otp/<code:[^/]+>/verify.html' => 'payment/wallet/otp-verify',
        'otp/captcha' => 'payment/wallet/captcha',
        'payment/<provider:[^/]+>/calc' => 'payment/installment/calculator',
        'payment/<provider:[^/]+>/check-field' => 'payment/validate/check-field',
        'payment/<merchant:[^/]+>/check-recursive' => 'payment/payment/check-recursive',
        'payment/courier/calculator' => 'payment/additional-fee-service/courier-calculator',
        'weshop/order/nicepaysuccess' => 'payment/payment/return-nicepay',
        'payment/callback/alepay' => 'payment/callback/alepay',
        'nicepay-payment.html' => 'payment/payment/return-nicepay',

        //account
        'my-weshop.html' => 'account/home',
        'my-account.html' => 'account/customer',
        'my-order.html' => 'account/order',
        'chat/<code:[^/]+>/order.html' => 'account/chat/order-chat',
        'login.html' => 'secure/login',
        'change-password.html' => 'secure/change-password',
        'signup.html' => 'secure/register',
        'logout.html' => 'secure/logout',

        //wallet
        'my-weshop/wallet.html' => 'account/wallet/index',
        'my-weshop/order/<orderCode:[0-9A-Za-z_-]*>.html' => 'account/order/view',
        'my-weshop/wallet/transaction/<transaction_code:[^/]+>/detail.html' => 'account/wallet/detail',
        'my-wallet/topup.html' => 'account/api/wallet-service/topup',
        'my-wallet/topup/<merchant:[^/]+>/return.html' => 'account/api/wallet-service/return',
        'my-wallet/withdraw.html' => 'account/api/wallet-service/withdraw',
        'my-wallet/sent-otp.html' => 'account/api/wallet-service/sent-otp',
        'my-wallet/cancel-withdraw.html' => 'account/api/wallet-service/cancel-withdraw',
        'my-weshop/wallet/withdraw/<transaction_code:[^/]+>.html' => 'account/wallet/withdraw',
        'my-weshop/<controller>/<action>.html' => 'account/<controller>/<action>',
        'my-weshop/api/<controller>/<action>.html' => 'account/api/<controller>/<action>',

    ]
];
