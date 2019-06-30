<?php
return [
    'account_kit' => [
        'store_1' => [
            'app_id' => '1909464509361286',
            'secret' => '3c652143186f587854f9fd405abd1aba',
            'ver' => 'v1.1',
            'code_phone' => '+84',
        ],
        'store_7' => [
            'app_id' => '181219292667675',
            'secret' => '8623d33f089a5f5e4a9fa5e48b08722e',
            'ver' => 'v1.1',
            'code_phone' => '+62',
        ],
        'store_2' => [
            'app_id' => '181219292667675',
            'secret' => '8623d33f089a5f5e4a9fa5e48b08722e',
            'ver' => 'v1.1',
            'code_phone' => '+62',
        ],
    ],
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
    'paymentClientParams' => [
        'nganluong_ver3_1' => [
            'enable' => 'sandbox',
            'params' => [
                'sandbox' => [
                    'URL' => 'https://sandbox.nganluong.vn:8088/nl35/checkout.api.nganluong.post.php',
                    'ACC' => 'dev.weshopasia@gmail.com',
                    'PASS' => '0f50829fb879fa9ecf480cb788ebb218',
                    'ID' => '45378'
                ],
                'prod_trunggian' => [
                    'URL' => 'https://www.nganluong.vn/checkout.api.nganluong.post.php',
                    'ACC' => 'trunggian.wsvn@gmail.com',
                    'PASS' => 'weshop@2015',
                    'ID' => '38176'
                ],
                'prod_esc' => [
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
            ]

        ],
        'nganluong_ver3_2' => [
            'enable' => 'product',
            'params' => [
                'sandbox' => [
                    'summitUrl' => 'http://sandbox.nganluong.vn:8088/nl30/checkoutseamless.api.nganluong.post.php',
                    'merchant_id' => '45378',
                    'merchant_password' => '0f50829fb879fa9ecf480cb788ebb218',
                    'receiver_email' => 'dev.weshopasia@gmail.com',
                ],
                'product' => [
                    'summitUrl' => 'https://www.nganluong.vn/checkoutseamless.api.nganluong.post.php',
                    'merchant_id' => '59955',
                    'merchant_password' => 'b52e9dceb91598582f8b3279aa238d7f',
                    'receiver_email' => 'phuchc@peacesoft.net',
                ]
            ]

        ],
        'mcpay' => [
            'enable' => 'sandbox',
            'params' => [
                'sandbox' => [
                    'baseUrl' => 'https://mcbill.sandbox.id.mcpayment.net',
                    'weshopdev' => 'weshopdev',
                    'verifyKey' => '92ccabfc283a8d6b8a5c0d3b656ad05e'
                ],
                'product' => [
                    'baseUrl' => 'https://mcbill.mcpayment.co.id',
                    'merchant' => 'weshop',
                    'verifyKey' => 'acf16cb67dcfcfb3c30033bb8739833b'
                ]
            ]
        ],
        'nicepay' => [
            'enable' => 'product',
            'params' => [
                'product' => [
                    'iMid' => 'WESHOP1122',
                    'iMidInstallment' => 'WESHOPINS2',
                    'merchantKey' => 'vnMmWGv8+Ao7P9iI3G9IdwQ1cefHOrryIa4ELPBTd/uTCXdW4R+vTfABNuM6ofeiokxG976f9Mh9YywR7WLEJQ==',
                    'merchantKeyInstallment' => 'p9tK0wDh/sodB9caI0eN/ZNNjgPw8qwBykqR7rlO/GAAxlLMY5EUbTvon6j83Iwwa5DDefC0V+kj//cS5Hikjw=='
                ]
            ]
        ],
        'alepay' => [
            'enable' => 'sandbox',
            'params' => [
                'baseUrl' => 'https://alepay-sandbox.nganluong.vn/checkout/v1',
                'apiKey' => 'g84sF7yJ2cOrpQ88VbdZoZfiqX4Upx',
                'checksumKey' => 'lXntf6CIZbSgzMqTz1nQ11jPKhGfsF',
                'encryptKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKWYg7jKrTqs83iIvYxlLgMqIy4MErNsoBKU2MHaG5ccntzGeNcDba436ds+VWB4E9kaL+D2wTuaiU+4Hx7DcyJ3leksXXM85koV/97f8Gn4nd3epxucaurcXmcEvU/VfqU7bKTdLdLwB7yPaZ45ilmBh/GqGJnmfq9csVuyZ0cwIDAQAB',
                'callbackUrl' => '',
            ]
        ]
    ],
    'pickupUSWHGlobal' => [
        'default' => 'sandbox',
        'warehouses' => [
            'sandbox' => [
                'ref_user_id' => 23,
                'ref_pickup_id' => 35549,
                'email' => '',
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'

            ],
            'ws_vn' => [
                'ref_user_id' => 248341,
                'ref_pickup_id' => 62555,
                'email' => '',
                'name' => 'BMUS_NY',
                'description' => 'Boxme US',
                'address' => 'US · Newyork, United State'
            ],
            'ws_id' => [
                'ref_user_id' => 248341,
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
