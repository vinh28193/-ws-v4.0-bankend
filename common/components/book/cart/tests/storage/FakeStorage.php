<?php

namespace common\components\book\cart\tests\storage;

use common\components\book\cart\storage\StorageInterface;

class FakeStorage implements StorageInterface
{
    private $items = [];

    public function load()
    {
        return $this->items;
    }

    public function save(array $items)
    {
        $this->items = $items;
    }
}
