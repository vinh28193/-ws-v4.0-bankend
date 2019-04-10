<?php


namespace common\helpers;

use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;

class ObjectHelper
{

    /**
     * @param $object object|\stdClass|\yii\base\BaseObject
     * @param $key
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function resolve($object, $key)
    {
        $reflection = new ReflectionObject($object);
        $getter = "get" . Inflector::camelize($key);
        if ($reflection->hasProperty($key)) {
            return $object->$key;
        } elseif ($reflection->hasMethod($key)) {
            return call_user_func([$object, $key]);
        } elseif ($reflection->hasMethod($getter)) {
            return call_user_func([$object, $getter]);
        } else {
            throw new InvalidConfigException("can not resolve property or method $key in " . get_class($object));
        }
    }

    public static function toArray($object)
    {
        $reflection = new ReflectionObject($object);
        $result = [];
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $value = $reflectionProperty->getValue($object);
            $result[$reflectionProperty->getName()] = $value;
        }
        return $result;
    }
}