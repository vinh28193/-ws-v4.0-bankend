<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 11:12
 */

return [
    'visaMaster' => [
        'name' => 'Visa Master',
        'username' => 'username',
        'password' => 'password',
        'submit_url' => 'http://weshop-4.0.api.vn',
        'methods' => [
            'visa' => [
                'name' => 'visa',
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
            'master' => [
                'name' => 'master',
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
        ]
    ],
    'wallet' => [
        'name' => 'WS Wallet',
        'submit_url' => 'http://weshop-4.0.api.vn',
        'methods' => [],
    ]
];