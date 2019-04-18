<?php

/**
 * mock $_POST
 * json_decode($_POST,true);
 * dec:
 *      nhập mã coupon TEST100,
 *      thanh toán qua VCB22
 *      tổng tiền 10.000.000
 *      gồm có 2 orders
 */

/**
 * giảm 200k weshop_fee cho đơn hàng Ebay, mà giá trị từ 4.000.000 trở lên
 */
return [
    'couponCode' => 'test',
    'paymentService' => 'VCB_22',
    'customerId' => 1,
    'totalAmount' => 17000000,
    'orders' => [
        'order1' => [
            'itemType' => 'ebay',
            'shippingWeight' => 12,
            'shippingQuantity' => 3,
            'categoryId' => 23456,
            'totalAmount' => 4000000,
            'products' => [
                'product1' => [
                    'itemType' => 'ebay',
                    'shippingWeight' => 7,
                    'shippingQuantity' => 2,
                    'categoryId' => 23456,
                    'totalAmount' => 3000000,
                    'additionalFees' => [
                        'weshop_fee' => 600000,
                        'intl_shipping_fee' => 400000
                    ],
                ],
                'product2' => [
                    'itemType' => 'ebay',
                    'shippingWeight' => 5,
                    'shippingQuantity' => 1,
                    'categoryId' => 23456,
                    'totalAmount' => 1000000,
                    'additionalFees' => [
                        'weshop_fee' => 400000,
                        'intl_shipping_fee' => 700000
                    ],
                ]
            ]
        ],
        'order2' => [
            'itemType' => 'ebay',
            'shippingWeight' => 8,
            'shippingQuantity' => 4,
            'categoryId' => 23456,
            'totalAmount' => 2000000,
            'products' => [
                'product1' => [
                    'itemType' => 'ebay',
                    'shippingWeight' => 8,
                    'shippingQuantity' => 4,
                    'categoryId' => 23456,
                    'totalAmount' => 2000000,
                    'additionalFees' => [
                        'weshop_fee' => 500000,
                        'intl_shipping_fee' => 600000
                    ]
                ]
            ]
        ],
        'order3' => [
            'itemType' => 'ebay',
            'shippingWeight' => 8,
            'shippingQuantity' => 4,
            'categoryId' => 23456,
            'totalAmount' => 5000000,
            'products' => [
                'product1' => [
                    'itemType' => 'ebay',
                    'shippingWeight' => 16,
                    'shippingQuantity' => 1,
                    'categoryId' => 23456,
                    'totalAmount' => 5000000,
                    'additionalFees' => [
                        'weshop_fee' => 300000,
                        'intl_shipping_fee' => 900000
                    ]
                ]
            ]
        ],
        'order4' => [
            'itemType' => 'amazon',
            'shippingWeight' => 8,
            'shippingQuantity' => 4,
            'categoryId' => 23456,
            'totalAmount' => 6000000,
            'products' => [
                'product1' => [
                    'itemType' => 'amazon',
                    'shippingWeight' => 8,
                    'shippingQuantity' => 4,
                    'categoryId' => 23456,
                    'totalAmount' => 6000000,
                    'additionalFees' => [
                        'weshop_fee' => 500000,
                        'intl_shipping_fee' => 600000
                    ]
                ]
            ]
        ]
    ]
];