<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 13:19
 */

namespace common\helpers;

use yii\base\InvalidArgumentException;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class WeshopHelper
{

    public static function response($success = true, $message = 'Ok', $data = [])
    {
        return ['success' => $success, 'message' => $message, 'data' => $data];
    }

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
        if ($haystack === null) {
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

    public static function strReplace($search, $replace, $subject, &$count = null)
    {
        return str_replace($search, $replace, $subject, $count);
    }

    /**
     * so sánh giá trị
     * @param $target
     * @param null $source
     * @param string $convertType
     * @param string $operator
     * @return bool|mixed
     */
    public static function compareValue($target, $source = null, $convertType = 'string', $operator = '===')
    {
        if ($source === null) {
            return false;
        }
        // Todo add valid convert type
        if (!in_array($convertType, ['string', 'int', 'float', 'integer'])) {
            throw new InvalidArgumentException("invalid $convertType");
        }
        $condition = "return ($convertType)'$target' $operator ($convertType)'$source';";
        //var_dump(eval("return ($convertType)'$target';"));die;
        return eval($condition);
    }

    public static function generateTag($reference, $prefix = 'WS', $length = 16)
    {
        $length -= strlen($prefix);
        $length -= strlen($reference . "");
        if ($length > 1) {
            $reference .= substr(md5($reference), 0, $length - 1);
        }

        return self::strToUpperCase($prefix . $reference);
    }

    public static function discountAmountPercent($itemAmount, $orderAmount, $discountAmount)
    {
        $percent = ($itemAmount / $orderAmount) * 100;
        $percent = self::roundNumber($percent, 2);
        return ($discountAmount * $percent) / 100;
    }

    public static function imageList($name, $selection = null, $items = [], $options = [])
    {
        $formatter = ArrayHelper::remove($options, 'item');
        $itemOptions = ArrayHelper::remove($options, 'itemOptions', []);
        $encode = ArrayHelper::remove($options, 'encode', true);
        $separator = ArrayHelper::remove($options, 'separator', "\n");
        $tag = ArrayHelper::remove($options, 'tag', 'ul');

        $lines = [];
        $index = 0;
        foreach ($items as $value => $src) {
            $active = $selection !== null &&
                (!ArrayHelper::isTraversable($selection) && !strcmp($value, $selection)
                    || ArrayHelper::isTraversable($selection) && ArrayHelper::isIn((string)$value, $selection));
            if ($formatter !== null) {
                $lines[] = call_user_func($formatter, $index, $src, $name, $active, $value);
            } else {
                $lines[] = '<li><span>' . Html::img($src, array_merge([
                        'value' => $value,
                        'style' => 'width:100px'
                    ], $itemOptions)) . '</span></li>';
            }
            $index++;
        }
        $visibleContent = implode($separator, $lines);


        return Html::tag($tag, $visibleContent, $options);
    }
    public static function alias($str = NULL)
    {
        $str = self::removeutf8($str);
        $str = preg_replace('/[^a-zA-Z0-9-]/i', '', $str);
        $str = str_replace(array(
            '------------------',
            '-----------------',
            '----------------',
            '---------------',
            '--------------',
            '-------------',
            '------------',
            '-----------',
            '----------',
            '---------',
            '--------',
            '-------',
            '------',
            '-----',
            '----',
            '---',
            '--',
        ),
            '-',
            $str
        );
        $str = str_replace(array(
            '------------------',
            '-----------------',
            '----------------',
            '---------------',
            '--------------',
            '-------------',
            '------------',
            '-----------',
            '----------',
            '---------',
            '--------',
            '-------',
            '------',
            '-----',
            '----',
            '---',
            '--',
        ),
            '-',
            $str
        );
        if (!empty($str)) {
            if ($str[strlen($str) - 1] == '-') {
                $str = substr($str, 0, -1);
            }
            if ($str[0] == '-') {
                $str = substr($str, 1);
            }
        }
        return strtolower($str);
    }

    public static function removeutf8($str = NULL)
    {
        $chars = array(
            'a' => array('ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'á', 'à', 'ả', 'ã', 'ạ', 'â', 'ă', 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Â', 'Ă'),
            'e' => array('ế', 'ề', 'ể', 'ễ', 'ệ', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê'),
            'i' => array('í', 'ì', 'ỉ', 'ĩ', 'ị', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị'),
            'o' => array('ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'Ố', 'Ồ', 'Ổ', 'Ô', 'Ộ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ơ', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ơ'),
            'u' => array('ứ', 'ừ', 'ử', 'ữ', 'ự', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư'),
            'y' => array('ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'),
            'd' => array('đ', 'Đ'),
            '-' => array(' '),
        );
        foreach ($chars as $key => $arr) {
            foreach ($arr as $val) {
                $str = str_replace($val, $key, $str);
            }
        }
        return $str;
    }

    public static function generateUrlDetail($portal,$name,$sku,$sid = null){
        return '/'.$portal.'/item/'.self::alias($name).'-'.$sku.'.html';
    }

    /**
     * @param $amount
     * @param int $country // 1: Viet Nam, 2: US, 7: Indo. Dựa theo store id
     * @param string $symbol
     * @param int $round
     */
    public static function showMoney($amount, $country = 1, $symbol = '',$round = 0){
        switch ($country){
            case 1:
                $symbol = $symbol ? $symbol : 'đ';
                $floorNumber = $round ? $round : 1000;
                $price = $amount / $floorNumber;
                $roundPrice = round($price);
                $finalPrice = $floorNumber * $roundPrice;
                return number_format($finalPrice, 0, ',', '.') . ' '.$symbol;
                break;
            case 2:
                $symbol = $symbol ? $symbol : '$';
                $floorNumber = $round ? $round : 2;
                $price = $amount / $floorNumber;
                $roundPrice = round($price);
                $finalPrice = $floorNumber * $roundPrice;
                return $symbol.' '.number_format($finalPrice, 2, '.', ',');
                break;
            case 7:
                $symbol = $symbol ? $symbol : 'RP';
                $floorNumber = $round ? $round : 0;
                $price = $amount / $floorNumber;
                $roundPrice = round($price);
                $finalPrice = $floorNumber * $roundPrice;
                return $symbol.' '.number_format($finalPrice, 0, '.', ',');
                break;
            default:
                $symbol = $symbol ? $symbol : 'đ';
                $floorNumber = $round ? $round : 1000;
                $price = $amount / $floorNumber;
                $roundPrice = round($price);
                $finalPrice = $floorNumber * $roundPrice;
                return number_format($finalPrice, 0, ',', '.') . ' '.$symbol;
                break;
        }
    }
}