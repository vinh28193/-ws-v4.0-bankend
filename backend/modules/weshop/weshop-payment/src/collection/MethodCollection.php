<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 08:45
 */

namespace weshop\payment\collection;


class MethodCollection extends \yii\base\BaseObject implements \IteratorAggregate, \ArrayAccess, \Countable
{

    public $_methods = [];

    /**
     * Returns an iterator for traversing the methods in the collection.
     * This method is required by the SPL interface [[\IteratorAggregate]].
     * It will be implicitly called when you use `foreach` to traverse the collection.
     * @return \ArrayIterator an iterator for traversing the methods in the collection.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_methods);
    }

    /**
     * Returns the number of methods in the collection.
     * This method is required by the SPL `Countable` interface.
     * It will be implicitly called when you use `count($collection)`.
     * @return int the number of methods in the collection.
     */
    public function count()
    {
        return $this->getCount();
    }

    /**
     * Returns the number of methods in the collection.
     * @return int the number of methods in the collection.
     */
    public function getCount()
    {
        return count($this->_methods);
    }

    /**
     * Returns the named method(s).
     * @param string $name the name of the method to return
     * @param mixed $default the value to return in case the named method does not exist
     * @param bool $first whether to only return the first method of the specified name.
     * If false, all methods of the specified name will be returned.
     * @return string|array the named method(s). If `$first` is true, a string will be returned;
     * If `$first` is false, an array will be returned.
     */
    public function get($name, $default = null, $first = true)
    {
        $name = strtolower($name);
        if (isset($this->_methods[$name])) {
            return $first ? reset($this->_methods[$name]) : $this->_methods[$name];
        }

        return $default;
    }

    /**
     * Adds a new method.
     * If there is already a method with the same name, it will be replaced.
     * @param string $name the name of the method
     * @param string $value the value of the method
     * @return $this the collection object itself
     */
    public function set($name, $value)
    {
        $name = strtolower($name);
        $this->_methods[$name] = (array) $value;

        return $this;
    }

    /**
     * Adds a new method.
     * If there is already a method with the same name, the new one will
     * be appended to it instead of replacing it.
     * @param string $name the name of the method
     * @param string $value the value of the method
     * @return $this the collection object itself
     */
    public function add($name, $value)
    {
//        $name = strtolower($name);
        $this->_methods[$name] = $value;

        return $this;
    }

    /**
     * Sets a new method only if it does not exist yet.
     * If there is already a method with the same name, the new one will be ignored.
     * @param string $name the name of the method
     * @param string $value the value of the method
     * @return $this the collection object itself
     */
    public function setDefault($name, $value)
    {
        $name = strtolower($name);
        if (empty($this->_methods[$name])) {
            $this->_methods[$name][] = $value;
        }

        return $this;
    }

    /**
     * Returns a value indicating whether the named method exists.
     * @param string $name the name of the method
     * @return bool whether the named method exists
     */
    public function has($name)
    {
        $name = strtolower($name);

        return isset($this->_methods[$name]);
    }

    /**
     * Removes a method.
     * @param string $name the name of the method to be removed.
     * @return array the value of the removed method. Null is returned if the method does not exist.
     */
    public function remove($name)
    {
        $name = strtolower($name);
        if (isset($this->_methods[$name])) {
            $value = $this->_methods[$name];
            unset($this->_methods[$name]);
            return $value;
        }

        return null;
    }

    /**
     * Removes all methods.
     */
    public function removeAll()
    {
        $this->_methods = [];
    }

    /**
     * Returns the collection as a PHP array.
     * @return array the array representation of the collection.
     * The array keys are method names, and the array values are the corresponding method values.
     */
    public function toArray()
    {
        return $this->_methods;
    }

    /**
     * Populates the method collection from an array.
     * @param array $array the methods to populate from
     * @since 2.0.3
     */
    public function fromArray(array $array)
    {
        $this->_methods = $array;
    }

    /**
     * Returns whether there is a method with the specified name.
     * This method is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `isset($collection[$name])`.
     * @param string $name the method name
     * @return bool whether the named method exists
     */
    public function offsetExists($name)
    {
        return $this->has($name);
    }

    /**
     * Returns the method with the specified name.
     * This method is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$method = $collection[$name];`.
     * This is equivalent to [[get()]].
     * @param string $name the method name
     * @return string the method value with the specified name, null if the named method does not exist.
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Adds the method to the collection.
     * This method is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$collection[$name] = $method;`.
     * This is equivalent to [[add()]].
     * @param string $name the method name
     * @param string $value the method value to be added
     */
    public function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Removes the named method.
     * This method is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `unset($collection[$name])`.
     * This is equivalent to [[remove()]].
     * @param string $name the method name
     */
    public function offsetUnset($name)
    {
        $this->remove($name);
    }
}