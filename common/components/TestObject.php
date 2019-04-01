<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 13:19
 */

namespace common\components;


class TestObject extends \yii\base\BaseObject
{

    public $type;

    public function __call($name, $arguments)
    {
        $check = $this->type === $name ? 'true' : 'false';
        // Note: value of $name is case sensitive.
        echo "Calling object method `$check`"
            . implode(', ', $arguments). "\n";
    }
}