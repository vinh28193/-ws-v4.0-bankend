<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 13:59
 */

namespace common\components\cart\serialize;


abstract class BaseCartSerialize extends \yii\base\BaseObject
{

    /**
     * @param $value
     * @return mixed
     */
    abstract function serializer($value);

    /**
     * @param $value
     * @return mixed
     */
    abstract function unserialize($value);
}