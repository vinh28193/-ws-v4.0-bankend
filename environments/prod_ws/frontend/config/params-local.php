<?php
return [
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail'=> 'csadmin@weshop.com.vn',
    'Url_User_Back_end'=>'http://s.weshop.asia',
    'Url_FrontEnd'=>'http://v3.weshop.com.vn',
    'Url_wallet_api' => 'http://v3.weshop.com.vn',
    'ENV' => true, // True --> envaroment Develop , false : Prod
    'api_login_boxme' => 'https://s.boxme.asia/api/v1/users/auth/sign-in/',
    'api_addresse_warehouse' => 'https://s.boxme.asia/api/v1/sellers/addresses/default-warehouse/',
    'pickupUSWHGlobal' => [
        'default' => 'ws_vn',
    ],

     'paymentClientParams' => [
        'nganluong_ver3_1' => [
            'enable' => 'prod_trunggian',
            'params' => [
                'prod_trunggian' => [
                    'URL' => 'https://www.nganluong.vn/checkout.api.nganluong.post.php',
                    'ACC' => 'buynow@weshop.asia',
                    'PASS' => '8a9e7f1add29b10c6588fc9517e4c402',
                    'ID' => '60075'
                ],
            ]

        ],
        'nganluong_ver3_2' => [
            'enable' => 'productBM',
            'params' => [
                'product' => [
                    'summitUrl' => 'https://www.nganluong.vn/checkoutseamless.api.nganluong.post.php',
                    'merchant_id' => '59955',
                    'merchant_password' => 'b52e9dceb91598582f8b3279aa238d7f',
                    'receiver_email' => 'phuchc@peacesoft.net',
                ],
                'productBM' => [
                    'summitUrl' => 'https://www.nganluong.vn/checkoutseamless.api.nganluong.post.php',
                    'merchant_id' => '60126',
                    'merchant_password' => '55df210eba73fed2d7627ae0817c16bb',
                    'receiver_email' => 'buynow@weshop.asia',
                ]
            ],
        ],
         'alepay' => [
             'enable' => 'live',
             'params' => [
                 'sandbox' => [
                     'baseUrl' => 'https://alepay-sandbox.nganluong.vn/checkout/v1',
                     'apiKey' => 'g84sF7yJ2cOrpQ88VbdZoZfiqX4Upx',
                     'checksumKey' => 'lXntf6CIZbSgzMqTz1nQ11jPKhGfsF',
                     'encryptKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKWYg7jKrTqs83iIvYxlLgMqIy4MErNsoBKU2MHaG5ccntzGeNcDba436ds+VWB4E9kaL+D2wTuaiU+4Hx7DcyJ3leksXXM85koV/97f8Gn4nd3epxucaurcXmcEvU/VfqU7bKTdLdLwB7yPaZ45ilmBh/GqGJnmfq9csVuyZ0cwIDAQAB',
                     'callbackUrl' => '',
                 ],
                 'live' => [
                     'baseUrl' => 'https://alepay.vn/checkout/v1',
                     'apiKey' => 'K4LIZPybs0mcHegtN3vMqeWTDsGO32',
                     'checksumKey' => 'R5l8bgiBBPoQ16UD3Q8UQ2x7TctvOg',
                     'encryptKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCqWpoASXBQ6zMI2uWOZNCm8haZMdhM2lA8d4CKLDMmP6OkXH20CQJv3e++aKdNOZFGyBypC3kepJFjhizwvMLQCMKYQY2Dt/A/lymAI9BC90ZZtJMvZ5hILl5qVJTd4MtidVaIMUN+ALC2vcGIeupqdq5Rw3BL4pJBqVghp6iKiwIDAQAB',
                     'callbackUrl' => 'https://weshop.com.vn/payment/callback/alepay',
                 ]
             ]
         ]
    ],
];
