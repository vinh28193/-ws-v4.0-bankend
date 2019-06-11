<?php
return [
    'adminEmail' => 'admin@example.com',
    'jsMessages' => [
        'Hello {name}',
        'Error', 'Success', 'Confirm', 'Delete', 'Select', 'Not Found',
        'Cannot change quantity',
        'You can not buy greater than {number}',
        'You can not buy lesser than {number}',
        'Out of stock',
        'Please select the variation',
        'Please fill full the buyer information',
        'Please fill full the receiver information',
        'You must agree to the {name} terms',
        'You need to top up over {amount}',
        'You have not agreed to {name}\'s terms and conditions of trading'
    ],
    'paymentProviderActiveEnv' => 'sandbox',
    'paymentClientParams' => [
        'nganluong_ver3.1' => [
            'sandbox' => [
                'URL' => 'http://sandbox.nganluong.vn:8088/nl30/paygate.weshop.php',
                'ACC' => 'dev.weshopasia@gmail.com',
                'PASS' => '0f50829fb879fa9ecf480cb788ebb218',
                'ID' => '45378'
            ],
            'prod_trunggian' => [
                'URL' => 'https://www.nganluong.vn/paygate.weshop.php',
                'ACC' => 'trunggian.wsvn@gmail.com',
                'PASS' => 'weshop@2015',
                'ID' => '38176'
            ],
            'prod_esc'=>[
                'URL' => 'https://www.nganluong.vn/checkout.api.nganluong.post.php',
                'ID' => '38176',
                'PASS' => 'ee7d2228e2f33598db0a78bb22e2ad10',
                'ACC' => 'esc.wsvn@gmail.com'
            ],
            'sandbox_esc' => [
                'URL' => 'https://sandbox.nganluong.vn:8088/nl30/checkout.api.nganluong.post.php',
                'ID' => '46048',
                'PASS' => '3c245b0d9de0b2358fa29f0a8f067557',
                'ACC' => 'weshopesc@gmail.com'
            ]
        ],
        'nganluong_ver3.2' => [
            'sandbox' => [
                'baseUrl' => 'http://sandbox.nganluong.vn:8088/nl30',
                'merchant_id' => '45378',
                'merchant_password' => '0f50829fb879fa9ecf480cb788ebb218',
                'receiver_email' => 'dev.weshopasia@gmail.com',
            ],
            'product' => [
                'baseUrl' => 'https://www.nganluong.vn',
                'merchant_id' => '38176',
                'merchant_password' => 'weshop@2015',
                'receiver_email' => 'trunggian.wsvn@gmail.com',
            ]
        ],
        'alepay' => [

        ]
    ]
];
