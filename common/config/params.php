<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'CACHE_PAYMENT_PROVINDER' => 'CACHE_PAYMENT_PROVINDER',
    'CACHE_PAYMENT_METHOD' => 'CACHE_PAYMENT_METHOD',
    'CACHE_PAYMENT_METHOD_BY_ID_' => 'CACHE_PAYMENT_METHOD_BY_ID_',
    'CACHE_WALLET_CLIENT_BY_CUSTOMER_ID_' => 'CACHE_WALLET_CLIENT_BY_CUSTOMER_ID_',
    'CACHE_CUSTOMER_BY_PK_' => 'CACHE_CUSTOMER_BY_PK_',
    'QUEUE_TRANSACTION_WALLET_NL' => 'QUEUE_TRANSACTION_WALLET_NL',
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
        'default' => 'sandbox_vn',
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
        ]

    ]
];
