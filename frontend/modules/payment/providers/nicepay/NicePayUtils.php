<?php


namespace frontend\modules\payment\providers\nicepay;


class NicePayUtils
{

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