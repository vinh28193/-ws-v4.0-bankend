<?php

namespace common\tests\fixtures;

class StoreFixture extends \common\fixtures\StoreFixture
{
    public $dataFile = '@common/tests/fixtures/data/store.php';
    public $depends = [
        'common\tests\fixtures\SystemCountryFixture',
    ];
}