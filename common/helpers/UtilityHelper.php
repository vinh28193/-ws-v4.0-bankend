<?php


namespace common\helpers;

use Yii;

class UtilityHelper
{


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
}