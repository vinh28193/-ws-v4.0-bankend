<?php


namespace frontend\modules\payment\providers\nganluong\ver3_2;


use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use yii\base\BaseObject;

class NganLuongParamCollection extends BaseObject implements IteratorAggregate, ArrayAccess, Countable
{
    private $_params = [];

    public function getIterator()
    {
        return new ArrayIterator($this->_params);
    }

    public function count()
    {
        return count($this->_params);
    }

    public function get($key, $default = null)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
        return $default;
    }

    public function set($key, $value)
    {
        $this->_params[$key] = $value;
        return $this;
    }

    public function setDefault($key, $value)
    {
        if (empty($this->_params[$key])) {
            $this->_params[$key] = $value;
        }

        return $this;
    }

    public function has($key)
    {
        return isset($this->_params[$key]);
    }

    public function remove($key, $default = null)
    {
        if (isset($this->_params[$key])) {
            $value = $this->_params[$key];
            unset($this->_params[$key]);
            return $value;
        }

        return $default;
    }

    public function removeAll()
    {
        $this->_params = [];
    }

    public function toArray()
    {
        return $this->_params;
    }


    public function fromArray(array $array)
    {
        $this->_params = $array;
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    public function create()
    {
        return NganluongHelper::buildParams($this->_params);
    }
}