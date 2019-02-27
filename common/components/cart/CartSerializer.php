<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 15:21
 */

namespace common\components\cart;


class CartSerializer extends \yii\base\BaseObject
{

    public function serializer($data)
    {
        return serialize($data);
    }

    public function unserialize($data)
    {
        return unserialize($data);
    }
}