<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 15:49
 */

namespace common\components\cart\serialize;


class NoneSerialize extends BaseCartSerialize
{
    /**
     * @param $value
     * @return mixed|string
     */
    public function serializer($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function unserialize($value)
    {

        return $value;
    }
}