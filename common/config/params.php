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
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'
            ],
            'ws_id' => [
                'ref_user_id' => 253019,
                'ref_pickup_id' => 62556,
                'email' => '',
                'name' => 'BMUS_NY',
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
