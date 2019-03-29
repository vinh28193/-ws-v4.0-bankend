<?php

namespace common\components\book\cart\storage;

interface StorageInterface
{
    /**
     * @return array
     */
    public function load();

    /**
     * @param array $items
     */
    public function save(array $items);
}
