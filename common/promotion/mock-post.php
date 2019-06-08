<?php

/**
 * mock $_POST
 * json_decode($_POST,true);
 */

return [
    'couponCode' => 'TEST201',
    'paymentService' => 'VCB_22',
    'totalAmount' => 17000000,
    'additionalFees' => [
        'weshop_fee' => 600000,
        'intl_shipping_fee' => 400000
    ],
];