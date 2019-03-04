<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 09:17
 */

namespace weshop\payment\collection;


class ProviderCollection extends \yii\base\BaseObject implements \IteratorAggregate, \ArrayAccess, \Countable
{

    public $_providers = [];

    /**
     * Returns an iterator for traversing the providers in the collection.
     * This provider is required by the SPL interface [[\IteratorAggregate]].
     * It will be implicitly called when you use `foreach` to traverse the collection.
     * @return \ArrayIterator an iterator for traversing the providers in the collection.
     */
    public function getIterator()
{
    return new \ArrayIterator($this->_providers);
}

    /**
     * Returns the number of providers in the collection.
     * This provider is required by the SPL `Countable` interface.
     * It will be implicitly called when you use `count($collection)`.
     * @return int the number of providers in the collection.
     */
    public function count()
{
    return $this->getCount();
}

    /**
     * Returns the number of providers in the collection.
     * @return int the number of providers in the collection.
     */
    public function getCount()
{
    return count($this->_providers);
}

    /**
     * Returns the named provider(s).
     * @param string $name the name of the provider to return
     * @param mixed $default the value to return in case the named provider does not exist
     * @param bool $first whether to only return the first provider of the specified name.
     * If false, all providers of the specified name will be returned.
     * @return string|array the named provider(s). If `$first` is true, a string will be returned;
     * If `$first` is false, an array will be returned.
     */
    public function get($name, $default = null, $first = true)
{
    $name = strtolower($name);
    if (isset($this->_providers[$name])) {
        return $first ? reset($this->_providers[$name]) : $this->_providers[$name];
    }

    return $default;
}

    /**
     * Adds a new provider.
     * If there is already a provider with the same name, it will be replaced.
     * @param string $name the name of the provider
     * @param string $value the value of the provider
     * @return $this the collection object itself
     */
    public function set($name, $value)
{
    $name = strtolower($name);
    $this->_providers[$name] = (array) $value;

    return $this;
}

    /**
     * Adds a new provider.
     * If there is already a provider with the same name, the new one will
     * be appended to it instead of replacing it.
     * @param string $name the name of the provider
     * @param string $value the value of the provider
     * @return $this the collection object itself
     */
    public function add($name, $value)
{
    $name = strtolower($name);
    $this->_providers[$name][] = $value;

    return $this;
}

    /**
     * Sets a new provider only if it does not exist yet.
     * If there is already a provider with the same name, the new one will be ignored.
     * @param string $name the name of the provider
     * @param string $value the value of the provider
     * @return $this the collection object itself
     */
    public function setDefault($name, $value)
{
    $name = strtolower($name);
    if (empty($this->_providers[$name])) {
        $this->_providers[$name][] = $value;
    }

    return $this;
}

    /**
     * Returns a value indicating whether the named provider exists.
     * @param string $name the name of the provider
     * @return bool whether the named provider exists
     */
    public function has($name)
{
    $name = strtolower($name);

    return isset($this->_providers[$name]);
}

    /**
     * Removes a provider.
     * @param string $name the name of the provider to be removed.
     * @return array the value of the removed provider. Null is returned if the provider does not exist.
     */
    public function remove($name)
{
    $name = strtolower($name);
    if (isset($this->_providers[$name])) {
        $value = $this->_providers[$name];
        unset($this->_providers[$name]);
        return $value;
    }

    return null;
}

    /**
     * Removes all providers.
     */
    public function removeAll()
{
    $this->_providers = [];
}

    /**
     * Returns the collection as a PHP array.
     * @return array the array representation of the collection.
     * The array keys are provider names, and the array values are the corresponding provider values.
     */
    public function toArray()
{
    return $this->_providers;
}

    /**
     * Populates the provider collection from an array.
     * @param array $array the providers to populate from
     * @since 2.0.3
     */
    public function fromArray(array $array)
{
    $this->_providers = $array;
}

    /**
     * Returns whether there is a provider with the specified name.
     * This provider is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `isset($collection[$name])`.
     * @param string $name the provider name
     * @return bool whether the named provider exists
     */
    public function offsetExists($name)
{
    return $this->has($name);
}

    /**
     * Returns the provider with the specified name.
     * This provider is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$provider = $collection[$name];`.
     * This is equivalent to [[get()]].
     * @param string $name the provider name
     * @return string the provider value with the specified name, null if the named provider does not exist.
     */
    public function offsetGet($name)
{
    return $this->get($name);
}

    /**
     * Adds the provider to the collection.
     * This provider is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `$collection[$name] = $provider;`.
     * This is equivalent to [[add()]].
     * @param string $name the provider name
     * @param string $value the provider value to be added
     */
    public function offsetSet($name, $value)
{
    $this->set($name, $value);
}

    /**
     * Removes the named provider.
     * This provider is required by the SPL interface [[\ArrayAccess]].
     * It is implicitly called when you use something like `unset($collection[$name])`.
     * This is equivalent to [[remove()]].
     * @param string $name the provider name
     */
    public function offsetUnset($name)
{
    $this->remove($name);
}
}