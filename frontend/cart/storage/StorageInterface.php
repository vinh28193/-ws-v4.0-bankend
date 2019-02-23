<?php

namespace frontend\cart\storage;

interface StorageInterface
{
    /**
     * @return array of cart items
     */
    public function load();

    /**
     * @param array $items from cart
     */
    public function save(array $items);
}