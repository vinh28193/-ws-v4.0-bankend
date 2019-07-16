<?php
return [
    'api_login_boxme' => 'https://s.boxme.asia/api/v1/users/auth/sign-in/',
    'version_gate' => '', //Api Chương: '' or Api WESHOP: 'Old'
    'ebay-api' => 'https://api-lbc.weshop.asia/v3',
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
    'url_api' => 'http://s.weshop.asia',
    'Url_User_Back_end' => 'http://weshop.v4.api.userbackend',
    'Url_FrontEnd' => 'http://v3.weshop.com.vn',
    'Url_wallet_api' => 'http://s.weshop.asia',
    'redis_queue' => [
        'host' => 'localhost',
        'port' => 6379,
    ],
    'list_method_withdraw' => ['NL','BANK'],
    'BOXME_GRPC_SERVICE_LOCATION' => '10.130.111.53:50052',
    'BOXME_GRPC_SERVICE_USER' => '10.130.111.53:50053',
    'BOXME_GRPC_SERVICE_ACCOUNTING' => '10.130.111.53:50054',
    'BOXME_GRPC_SERVICE_SHIPMENT' => '10.130.111.53:50060',
    'BOXME_GRPC_SERVICE_COURIER' => '10.130.111.53:50056',
    'BOXME_GRPC_SERVICE_CURRENCY' => '10.130.111.53:50060',
    'BOXME_GRPC_SERVICE_ORDER' => '10.130.111.53:50058',
    'BOXME_GRPC_SERVICE_SELLER' => '10.130.111.53:50060',
    'BOXME_GRPC_SERVICE_NOTIFICATION' => '10.130.111.53:50051',
    'BOXME_GRPC_SERVICE_OPS' => '10.130.111.53:50059',
    'checkBanIdEbay' => ['382770626640', '352606051815', '352606042519', '202610167604', '312512021549', '382813081554', '143155104903', '352606051815', '312504573941', '292983412384', '312512021549', '312504573941', '352606042519', '382813081554', '312476086414', '401713796928', '362500946994', '323720429607', '292983412384', '173332511300', '123662640287', '283176857304', '292958064996', '113465254100',],
    'pickupUSWHGlobal' => [
        'default' => 'ws_id',
        'warehouses' => [
            'sandbox_vn' => [
                'ref_user_id' => 23,
                'ref_pickup_id' => 35549,
                'email' => '',
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'

            ],
            'sandbox_id' => [
                'ref_user_id' => 23,
                'ref_pickup_id' => 35669,
                'email' => '',
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'

            ],
            'ws_vn' => [
                'ref_user_id' => 253019,
                'ref_pickup_id' => 62555,
                'email' => '',
                'name' => 'BMVN_US',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'
            ],
            'ws_id' => [
                'ref_user_id' => 253019,
                'ref_pickup_id' => 62556,
                'email' => '',
                'name' => 'BMID_US',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'
            ],
            'weshoptracking' => [
                'ref_user_id' => 377,
                'ref_pickup_id' => 35549,
                'email' => 'weshoptracking@gmail.com',
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'
            ]
        ],
    ],

];
