<?php


return [
    'weshop_fee' => [
        [
            'conditions' => [
                [
                    'value' => 450,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ],
                [
                    'value' => 'ebay',
                    'key' => 'portal',
                    'type' => 'string',
                    'operator' => '=='
                ]
            ],
            'type' => 'P',
            'value' => 12,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 450,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ],
                [
                    'value' => 'amazon',
                    'key' => 'portal',
                    'type' => 'string',
                    'operator' => '=='
                ]
            ],
            'type' => 'P',
            'value' => 10,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 450,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ],
                [
                    'value' => 'other',
                    'key' => 'portal',
                    'type' => 'string',
                    'operator' => '=='
                ]
            ],
            'type' => 'P',
            'value' => 10,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 450,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 750,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 10,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 750,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 1000,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 9,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 1000,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 1500,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 8.5,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 1500,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 2000,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 8,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 2000,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 2500,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 7,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 2500,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
                [
                    'value' => 3000,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ],
            'type' => 'P',
            'value' => 6,
            'unit' => 'price'
        ],
        [
            'conditions' => [
                [
                    'value' => 2500,
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>='
                ],
            ],
            'type' => 'P',
            'value' => 5,
            'unit' => 'price'
        ],
    ],
    'intl_shipping_fee' => [
        [
            'conditions' => [
                [
                    'value' => 0,
                    'key' => 'weight',
                    'type' => 'int',
                    'operator' => '>'
                ],
            ],
            'type' => 'F',
            'value' => 10,
            'unit' => 'weight'
        ],
    ],
    'custom_fee' => [
        [
            'value' => '5',
            'unit' => 'quantity',
            'type' => 'F',
            'conditions' => [
                [
                    'value' => '50',
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>',
                ],
            ],
        ],
        [
            'value' => '10',
            'unit' => 'quantity',
            'type' => 'F',
            'conditions' => [
                [
                    'value' => '50',
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<='
                ],
                [
                    'value' => '100',
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '>'
                ],
            ],
        ],
        [
            'value' => '10',
            'unit' => 'quantity',
            'type' => 'F',
            'conditions' => [
                [
                    'value' => '100',
                    'key' => 'price',
                    'type' => 'int',
                    'operator' => '<'
                ]
            ]
        ]
    ],
    'delivery_fee_local' => [
        [
            'conditions' => [
                [
                    'value' => 0,
                    'key' => 'quantity',
                    'type' => 'int',
                    'operator' => '>'
                ],
            ],
            'type' => 'F',
            'value' => 2,
            'unit' => 'quantity'
        ],
    ]
];