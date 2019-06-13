<?php

namespace frontend\modules\payment\providers\nganluong\ver3_2;

use common\components\XmlUtility;
use common\helpers\WeshopHelper;

class NganluongHelper
{
    const VERSION = '3.2';

    const PAYMENT_METHOD_ATM_ONLINE = 'ATM_ONLINE';
    const PAYMENT_METHOD_IB_ONLINE = 'IB_ONLINE';
    const PAYMENT_METHOD_QRCODE = 'QRCODE';
    const PAYMENT_METHOD_CASH_IN_SHOP = 'CASH_IN_SHOP';

    public static $methodPrefix = [
        self::PAYMENT_METHOD_ATM_ONLINE => 'ATM_',
        self::PAYMENT_METHOD_QRCODE => 'QRCODE_',
        self::PAYMENT_METHOD_IB_ONLINE => 'IB_'
    ];

    public static function buildParams($params)
    {
        $query = '';
        if(empty($params)){
            return $query;
        }
        $i = 1;
        foreach ($params as $key => $val) {
            $query .= ($i == 1 ? '' : '&') . $key . '=' . $val;
            $i++;
        }
        return $query;
    }

    public static function normalizeResponse($response)
    {
        return XmlUtility::xmlToArray($response);
    }

    public static function replaceBankCode($bankCode)
    {
        return str_replace(array_values(self::$methodPrefix), '', $bankCode);
    }

    public static function getPaymentMethod($bankCode)
    {
        $currentMethod = $bankCode;
        foreach (array_flip(self::$methodPrefix) as $prefix => $paymentMethod) {
            if (self::isSubText($prefix, $bankCode)) {
                $currentMethod = $paymentMethod;
                break;
            }
        }
        return $currentMethod;
    }

    private static function isSubText($key, $value)
    {
        return WeshopHelper::isSubText($value, $key);
    }
}