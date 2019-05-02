<?php


namespace common\helpers;

use Yii;

class UtilityHelper
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
     * $array1 = [
     *     'abc','def','def'
     * ];
     * $array2 =[
     *      10,15,12
     * ];
     * return [
     *      'abc' => 10,
     *      'def' => '27' //= 15 + 12
     * ]
     * @param array $key
     * @param array $value
     * @return array
     */
    public static function combine2Array($key, $value)
    {
        $array = [];
        foreach ($key as $index => $name){
            if(!isset($value[$index]) || ($realValue = $value[$index]) === null && $realValue <= 0){
                continue;
            }
            if(isset($array[$name]) && ($existValue = $array[$name]) !== null){
                $existValue += $realValue;
                $array[$name] = $existValue;
            }else{
                $array[$name] = $realValue;
            }
        }
        Yii::info($array,__METHOD__);
        return $array;
    }

    /**
     *  delimiter = '_';
     *  input eg : TEST123/TEST_123/TEST_A_123/TEST_1_123 ... etc format "string_number"
     *  return
     *      TEST123 => false
     *      TEST_123 => ['TEST',123]
     *      TEST_A_123 => ['TEST_A',123]
     *      TEST_1_123 => ['TEST_1',123]
     * @param $value
     * @param $delimiter
     * @return array|bool
     */

    public static function explodeByPreg($value, $delimiter = '_')
    {
        $pattern = "/([a-zA-z0-9_-]+)$delimiter([\d]+)+$/m";
        preg_match($pattern, $value, $match);
        if (is_array($match) && count($match) === 3) {
            return [$match[1], $match[2]];
        }
        return false;
    }

    public static function explodeSoiByPreg($value)
    {
        return self::explodeByPreg($value, '_');
    }

    public static function explodePackingCodePreg($value)
    {
        return self::explodeByPreg($value, '-');
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

    public static function strReplace($search, $replace, $subject, &$count = null){
        return str_replace($search, $replace, $subject, $count);
    }

    public static function clearText($value, $needle = ['*'])
    {
        return self::strReplace($needle, '', $value);
    }

    public static function isValidExcelValue($value)
    {
        if(self::isEmpty($value) || $value === '-' || $value === '_' || $value === '*'){
            return false;
        }
        return ($value = WeshopHelper::strToUpperCase($value)) !== 'NULL' &&  $value !== 'NONE' && $value !== 'NOT FOUND';
    }
}