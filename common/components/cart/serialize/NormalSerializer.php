<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 13:57
 */

namespace common\components\cart\serialize;

/**
 * Class NormalSerializer
 * @package common\components\cart\serialize
 */
class NormalSerializer extends BaseCartSerialize
{
    /**
     * @param $value
     * @return mixed|string
     */
    public function serializer($value)
    {
        return serialize($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function unserialize($value)
    {
        return unserialize($value);
    }
}