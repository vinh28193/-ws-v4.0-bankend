<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:44 PM
 */

namespace common\products;


class Option extends \yii\base\BaseObject
{

    public $name;
    public $value;

    public function init()
    {
        parent::init();
        $this->name = preg_replace('/[^A-Za-z0-9\-\. ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $this->name);
    }


}