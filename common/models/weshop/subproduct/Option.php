<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:44 PM
 */

namespace common\models\weshop\subproduct;


class Option
{
    public function __construct($data)
    {
        $productAttr = get_object_vars($this);
        foreach ($data as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_object($k1))
                    if ($k == $k1) {
                        $this->$k1 =preg_replace('/[^A-Za-z0-9\-\. ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $v) ;
                    }
            }
        }
    }
    public $name;
    public $value;
}