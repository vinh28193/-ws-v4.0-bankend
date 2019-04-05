<?php


namespace common\components\calculator;

use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;

class ResolveTarget
{
    /**
     * @param $target
     * @param $key
     * @return mixed
     * @throws InvalidConfigException
     */
    public function resolve($target, $key)
    {
        $reflection = ReflectionClass($target);
        $getter = "get" . Inflector::camelize($key);
        if ($reflection->hasProperty($key) || $reflection->hasMethod($key)) {
            return $target->$key;
        } elseif ($reflection->hasMethod($getter)) {
            return $target->$getter;
        } else {
            throw new InvalidConfigException("can not resolve property or method $key in " . get_class($target));
        }
    }
}