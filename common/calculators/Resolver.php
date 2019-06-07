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
            'price' => 'getTotalOriginPrice',
            'condition' => 'getIsNew',
            'portal' => 'getItemType',
            'user' => 'getUser'
        ];
        return isset($keys[$key]) ? $keys[$key] : $key;
    }
}