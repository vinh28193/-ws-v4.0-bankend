<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 16:35
 */

namespace common\rest;

class Serializer extends \yii\rest\Serializer
{

    public $collectionEnvelope = '_items';

    /**
     * @inheritdoc
     * @param mixed $data
     * @return array|mixed
     */
    public function serialize($data)
    {
        if (is_array($data) && count($data) === 3 && isset($data['data'])) {

            $data['data'] = $this->serialize($data['data']);
            return $data;
        } elseif ($data instanceof \IteratorAggregate) {
            return ($iterator = $data->getIterator()) instanceof \ArrayIterator ? $iterator->getArrayCopy() : $iterator;
        }
        $data = parent::serialize($data);
        if (is_object($data)) {
            $data = $this->serializeObject($data);
        }
        return $data;
    }

    public function serializeObject($data)
    {
        $reflection = new \ReflectionObject($data);
        $result = [];
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $value = $reflectionProperty->getValue($data);
            $value = $this->serialize($value);
            $result[$reflectionProperty->getName()] = $value;
        }
        return $result;
    }
}