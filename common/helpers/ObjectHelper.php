<?php


namespace common\helpers;

use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

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

    /**
     * @param $object object|\stdClass|\yii\base\BaseObject
     * @param $key string|array
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function resolveRecursive($object, $key)
    {
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $object = static::resolveRecursive($object, $keyPart);
            }
            $key = $lastKey;
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $object = static::resolveRecursive($object, substr($key, 0, $pos));
            $key = substr($key, $pos + 1);
        }

        return self::resolve($object, $key);
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