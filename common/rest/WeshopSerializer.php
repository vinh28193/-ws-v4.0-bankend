<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 16:35
 */

namespace common\components\rest;

class WeshopSerializer extends \yii\rest\Serializer
{

    public function serialize($data)
    {
        return parent::serialize($data);
    }
}