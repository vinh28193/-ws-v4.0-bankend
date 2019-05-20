<?php
return [
    'ebay-api' => 'http://192.168.11.252:8080/v3',
    'boxme' =>[
        'vn' => [
            'URL' => 'https://s.boxme.asia/api/',
            'TOKEN' => '424d7386b54575bb8bb75f1c6dacbd1a4f3257b83c1607a8ab635bc34f7c65bb'
        ],
        'id' => [
            'URL' => 'https://s.boxme.asia/api/',
            'TOKEN' => '424dcf510b055bc4345cafbe0e1e73da0a665ef236e965897ab48f165a599a19'
        ],
    ],
    'nganluong' => [
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
    'url_api' => 'http://weshop-v4.back-end.local.vn',
    'Url_User_Back_end' => 'http://weshop.v4.api.userbackend',
    'Url_FrontEnd' => 'http://weshop.v4.beta.vn',
    'Url_wallet_api' => 'http://wallet.weshop.v4.beta',
    'redis_queue' => [
        'host' => 'localhost',
        'port' => 6379,
    ],
];
