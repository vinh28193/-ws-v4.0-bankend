<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-06-22
 * Time: 09:48
 */

namespace common\wallet;


class WalletHelper
{
    /**
     * ```php
     * use common\wallet\WalletHelper;
     * $text = 'php tester';
     * $text = WalletHelper::hiddenText($text);
     * ```
     *  return 'ph****ter'
     * @param $string
     * @param string $magician
     * @return string
     */
    public static function hiddenText($string, $magician = '*')
    {
        $strlen = strlen($string);
        $half = $before = $after = floor($strlen / 2);
        $before = rand(1, $before - 1);
        $after = rand(1, $after - 1) + $half;
        $before = substr($string, 0, $before);
        $after = substr($string, $after, $strlen);
        $repeat = $strlen - strlen($before) - strlen($after);
        $repeat = str_repeat($magician, $repeat);
        return $before . $repeat . $after;
    }
}