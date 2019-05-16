<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 20/08/2018
 * Time: 13:57
 */

namespace common\models\payment\vietnam\alepaylib;


class AlepayCheckoutForm
{
    public $orderCode,
        $checkoutType = 0,
        $amount,
        $currency = 'VND', $orderDescription, $totalItem, $returnUrl, $cancelUrl, $buyerName, $buyerEmail, $buyerPhone, $buyerAddress, $buyerCity = '', $buyerCountry = 'VN', $paymentHours = 6, $installment = null, $month = null, $bankCode = null, $paymentMethod = null;

}