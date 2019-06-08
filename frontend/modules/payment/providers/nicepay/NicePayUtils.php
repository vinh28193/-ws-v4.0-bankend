<?php


namespace frontend\modules\payment\providers\nicepay;


class NicePayUtils
{
    const BANK_BMRI = 'BMRI';
    const AMOUNT_REQUIRED = 500000;
    const PAYMENT_METHOD = 59;

    public static function hashData($string)
    {
        return hash('sha256', $string);
    }

    public static function oneLine($errors)
    {
        // Return string in one line
        return implode(', ', $errors);
    }
}