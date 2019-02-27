<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 13:58
 */

namespace common\components\cart\serialize;

use yii\helpers\Json;

class JsonSerialize extends BaseCartSerialize
{
    /**
     * @param $value
     * @return mixed|string
     */
    public function serializer($value)
    {
        return Json::encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function unserialize($value)
    {

        return Json::decode($value);
    }
}