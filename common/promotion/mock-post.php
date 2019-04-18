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
 * giảm weshop_fee cho đơn hàng Ebay, mà giá trị hơn 2.000.000
 */
return [
    'couponCode' => 'test',
    'paymentService' => 'VCB_22',
    'customerId' => 1,
    'totalAmount' => 16000000,
    'orders' => [
        [
            'itemType' => 'ebay',
            'shippingWeight' => 12,
            'shippingQuantity' => 3,
            'categoryId' => 23456,
            'totalAmount' => 10000000,
            'products' => [
                [
                    'itemType' => 'ebay',
                    'shippingWeight' => 7,
                    'shippingQuantity' => 2,
                    'categoryId' => 23456,
                    'totalAmount' => 8000000,
                    'additionalFees' => [
                        'weshop_fee' => 600000,
                        'intl_shipping_fee' => 400000
                    ],
                ],
                [
                    'itemType' => 'ebay',
                    'shippingWeight' => 5,
                    'shippingQuantity' => 1,
                    'categoryId' => 23456,
                    'totalAmount' => 2000000,
                    'additionalFees' => [
                        'weshop_fee' => 400000,
                        'intl_shipping_fee' => 700000
                    ],
                ]
            ]
        ],
        [
            'itemType' => 'ebay',
            'shippingWeight' => 8,
            'shippingQuantity' => 4,
            'categoryId' => 23456,
            'totalAmount' => 6000000,
            'products' => [
                [
                    'itemType' => 'ebay',
                    'shippingWeight' => 8,
                    'shippingQuantity' => 4,
                    'categoryId' => 23456,
                    'totalAmount' => 4000000,
                    'additionalFees' => [
                        'weshop_fee' => 500000,
                        'intl_shipping_fee' => 600000
                    ]
                ]
            ]
        ],
        [
            'itemType' => 'amazon',
            'shippingWeight' => 8,
            'shippingQuantity' => 4,
            'categoryId' => 23456,
            'totalAmount' => 6000000,
            'products' => [
                [
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