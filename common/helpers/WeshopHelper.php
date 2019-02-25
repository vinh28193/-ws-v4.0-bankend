<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 13:19
 */

namespace common\helpers;

use DateTime;
use DateTimeZone;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

class WeshopHelper
{
    /**
     * define PHP empty value
     * @param $value
     * @return bool
     * @see PHP empty
     */
    public static function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || (is_string($value) && trim($value) === '');
    }

    /**
     * chỉ check tồn tại $needle trong $haystack
     * NOTE : không trả về vị trí nếu tồn tại
     * @param $haystack
     * @param $needle
     * @return bool
     * @see PHP strpos
     */
    public static function isSubText($haystack, $needle)
    {
        if($haystack === null){
            return false;
        }
        return strpos($haystack, $needle) !== false;
    }

    /**
     * Làm tròn từ số thập phân thứ n (n = $precision)
     * $number = 1234.56
     * $precision = 0  return 1235
     * $precision = 1 return 1234.6
     * $precision = -1 return 1230
     * $precision = 2 return 1234.56
     * $precision = -2 return 1200
     * @param $number
     * @param int $precision
     * @return float|int
     */
    public static function roundNumber($number, $precision = 0)
    {
        $factor = pow(10, $precision);
        return round($number * $factor) / $factor;
    }

    /**
     * @param $string
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @return mixed|string
     */
    public static function strToUpperCase($string, $trim = true)
    {
        $result = mb_strtoupper($string);
        if ($trim) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function ($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = call_user_func($trim, $result);
        }
        return $result;
    }

    /**
     * @param $variable1
     * @param $variable2
     * @param bool $caseSensitive
     * @return bool
     * @see UtilityHelper::compareValue()
     */
    public static function isDiffValue($variable1, $variable2, $caseSensitive = true)
    {
        return !$caseSensitive ? ($variable1 === $variable2) : strcasecmp($variable1, $variable2) === 0;
    }

    /**
     * check xem trong cùng 1 array cùng 1 key có giá trị khác nhau không
     * @param $arrays
     * @param null $key
     * @param bool $caseSensitive
     * @return bool
     */
    public static function isDiffArrayValue($arrays, $key = null, $caseSensitive = true)
    {
        if (count($arrays) === 1) {
            return false;
        }
        $firstItem = $arrays[0];

        if (($fixedValue = ArrayHelper::getValue($firstItem, $key, false)) === false) {
            return false;
        }
        unset($arrays[0]);
        foreach ($arrays as $item) {
            if (($checkValue = ArrayHelper::getValue($item, $key, false)) === false || (!self::isDiffValue($checkValue, $fixedValue, $caseSensitive))) {
                return true;
            }
        }
        return false;
    }

    public static function isValidExcelValue($value)
    {
        if(self::isEmpty($value) || $value === '-' || $value === '_' || $value === '*'){
            return false;
        }
        return ($value = self::strToUpperCase($value)) !== 'NULL' &&  $value !== 'NONE' && $value !== 'NOT FOUND';
    }

    public static function strReplace($search, $replace, $subject, &$count = null){
        return str_replace($search, $replace, $subject, $count);
    }
}