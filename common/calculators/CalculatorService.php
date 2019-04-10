<?php


namespace common\calculators;


class CalculatorService
{

    /**
     * @param $rules
     * @param $object
     * @return float|int
     */
    public static function calculator($rules, $object)
    {
        $results = 0;
        foreach ($rules as $rule) {
            $calc = new Calculator();
            $calc->register($rule);
            if (($results = $calc->calculator($object)) > 0){
                break;
            }
        }
        return $results;
    }

}