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

    public function hasItem($key);

    public function addItem($key, $value);

    public function setItem($key, $value);

    public function getItem($key);

    public function removeItem($key);

    public function getItems($identity);

    public function countItems($identity);

    public function removeItems($identity);

}