<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:40 PM
 */

namespace common\models\weshop\subproduct;


class Image
{
    public function __construct($data)
    {
        $productAttr = get_object_vars($this);
        foreach ($data as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_object($k1))
                    if ($k == $k1) {
                        $this->$k1 = $v;
                    }
            }
        }

    }

    public $thumb;
    public $main;
}