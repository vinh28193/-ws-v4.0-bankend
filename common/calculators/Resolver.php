<?php


namespace common\calculators;

use common\helpers\ObjectHelper;
use \yii\base\BaseObject;

class Resolver extends BaseObject
{

    /**
     * @param $object
     * @param $key
     * @return mixed
     */
    public function resolve($object, $key)
    {
        return ObjectHelper::resolve($object, $key);
    }

    public function resolveKey($key)
    {
        $keys = [
            'weight' => 'getShippingWeight',
            'quantity' => 'getShippingQuantity',
            'price' => 'getTotalOrigin',
            'new' => 'getIsNew',
            'special' => 'getIsSpecial',
            'portal' => 'getPortal',
            'level' => 'getUserLevel'
        ];
        return isset($keys[$key]) ? $keys[$key] : $key;
    }
}