<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:49
 */

namespace common\components\cart\storage;

interface CartStorageInterface
{



    /**
     * check item is exist
     * @param $key
     * @return boolean
     */
    public function hasItem($key);

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function addItem($key, $value);

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function setItem($key, $value);

    /**
     * @param $key
     * @return boolean|mixed
     */
    public function getItem($key);

    /**
     * @param $key
     * @return boolean|mixed
     */
    public function removeItem($key);

    /**
     * @param $keys
     * @param $identity
     * @return mixed
     */
    public function getItems($identity, $keys = null);

    /**
     * @param $identity
     * @return integer
     */
    public function countItems($identity);

    /**
     * @param $keys
     * @param $identity
     * @return int
     */
    public function removeItems($identity, $keys = null);

    public function keys($identity);

}