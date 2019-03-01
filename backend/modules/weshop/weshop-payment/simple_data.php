<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 11:12
 */

return [
    'visaMaster' => [
        'class' => 'weshop\payment\providers\simple\SimplePaymentProvider',
        'username' => 'username',
        'password' => 'password',
        'submit_url' => 'http://weshop-4.0.api.vn',
        'return_url' => 'http://weshop-4.0.api.vn',
        'cancel_url' => 'http://weshop-4.0.api.vn',
        'methods' => [
            'visaMaster' => [
                'class' => 'weshop\payment\methods\VisaMaster',
                'banks' => [
                    'vcb' => [
                        'name' => 'vcb',
                        'icon' => ''
                    ],
                    'tbc' => [
                        'name' => 'tcb',
                        'icon' => ''
                    ]
                ]
            ],
            'bankTransfer' => [
                'class' => 'weshop\payment\methods\BankTransfer',
                'banks' => [
                    'vcb' => [
                        'name' => 'vcb',
                        'icon' => ''
                    ],
                    'tbc' => [
                        'name' => 'tcb',
                        'icon' => ''
                    ]
                ]
            ]
        ]
    ],
    'wallet' => [
        'class' => 'weshop\payment\providers\wallet\WeshopWalletProvider',
        'methods' => [],
    ]
];