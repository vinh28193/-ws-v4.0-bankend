<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 15:19
 */

namespace  common\components;

use Countable;
use ArrayIterator;
use ArrayAccess;
use IteratorAggregate;
use yii\base\BaseObject;

/**
 * Class ArrayCollection
 * @package common\components
 * @property ArrayIterator $iterator An iterator for traversing the cookies in the collection. This property
 */

class ArrayCollection extends BaseObject implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var array the items in this collection (indexed by the item key)
     */
    private $_items = [];

    /**
     * Returns an iterator for traversing the items in the collection.
     * This item is required by the SPL interface [[\IteratorAggregate]].
     * It will be implicitly called when you use `foreach` to traverse the collection.
     * @return \ArrayIterator an iterator for traversing the items in the collection.
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_items);
    }

    /**
     * Returns the number of items in the collection.
     * This item is required by the SPL `Countable` interface.
     * It will be implicitly called when you use `count($collection)`.
     * @return int the number of items in the collection.
     */
    public function count()
    {
        return count($this->_items);
    }


    /**
     * Returns the keyd item(s).
     * @param string $key the key of the item to return
     * @param mixed $default the value to return in case the keyd item does not exist
     * @param bool $first whether to only return the first item of the specified key.
     * If false, all items of the specified key will be returned.
     * @return string|array the keyd item(s). If `$first` is true, a string will be returned;
     * If `$first` is false, an array will be returned.
     */
    public function get($key, $default = null, $first = true)
    {
        if (isset($this->_items[$key])) {
            return $first ? reset($this->_items[$key]) : $this->_items[$key];
        }

        return $default;
    }

    /**
     * Adds a new item.
     * If there is already a item with the same key, it will be replaced.
     * @param string $key the key of the item
     * @param mixed $value the value of the item
     * @return $this the collection object itself
     */
    public function set($key, $value)
    {
        $this->_items[$key] = (array)$value;
        return $this;
    }

    /**
     * Adds a new item.
     * If there is already a item with the same key, the new one will
     * be appended to it instead of replacing it.
     * @param string $key the key of the item
     * @param mixed $value the value of the item
     * @return $this the collection object itself
     */
    public function add($key, $value)
    {
        $this->_items[$key][] = $value;

        return $this;
    }

    /**
     * Sets a new item only if it does not exist yet.
     * If there is already a item with the same key, the new one will be ignored.
     * @param string $key the key of the item
     * @param string $value the value of the item
     * @return $this the collection object itself
     */
    public function setDefault($key, $value)
    {
        if (empty($this->_items[$key])) {
            $this->_items[$key] = $value;
        }

        return $this;
    }

    /**
     * Returns a value indicating whether the keyd item exists.
     * @param string $key the key of the item
     * @return bool whether the keyd item exists
     */
    public function has($key)
    {
        return isset($this->_items[$key]);
    }

    /**
     * Removes a item.
     * @param string $key the key of the item to be removed.
     * @param mixed $default the value to return in case the keyed item does not exist
     * @return array the value of the removed item. Null is returned if the item does not exist.
     */
    public function remove($key, $default = null)
    {
        if (isset($this->_items[$key])) {
            $value = $this->_items[$key];
            unset($this->_items[$key]);
            return $value;
        }

        return $default;
    }

    /**
     * Removes all items.
     */
    public function removeAll()
    {
        $this->_items = [];
    }

    /**
     * Returns the collection as a PHP array.
     * @return array the array representation of the collection.
     * The array keys are item keys, and the array values are the corresponding item values.
     */
    public function toArray()
    {
        return $this->_items;
    }

    /**
     * Populates the item collection from an array.
     * @param array $array the items to populate from
     * @since 2.0.3
     */
    public function fromArray(array $array)
    {
        $this->_items = $array;
    }

    /**
     * Returns whether there is a item with the specified key.
     * This item is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `isset($collection[$key])`.
     * @param string $key the item key
     * @return bool whether the keyd item exists
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Returns the item with the specified key.
     * This item is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$item = $collection[$key];`.
     * This is equivalent to [[get()]].
     * @param string $key the item key
     * @return string the item value with the specified key, null if the keyd item does not exist.
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Adds the item to the collection.
     * This item is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$collection[$key] = $item;`.
     * This is equivalent to [[add()]].
     * @param string $key the item key
     * @param string $value the item value to be added
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Removes the keyd item.
     * This item is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `unset($collection[$key])`.
     * This is equivalent to [[remove()]].
     * @param string $key the item key
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}