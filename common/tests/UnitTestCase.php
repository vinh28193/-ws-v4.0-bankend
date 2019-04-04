<?php

namespace common\tests;

use Codeception\Test\Unit;
use Codeception\Specify;

class UnitTestCase extends Unit
{
    use Specify;

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'store' => [
                'class' => 'common\tests\fixtures\StoreFixture'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function _setUp()
    {
        return parent::_setUp();
    }

    /**
     * @inheritDoc
     */
    protected function _tearDown()
    {
        parent::_tearDown();
    }

    /**
     * @param $fixtures
     */
    public function haveFixture($fixtures)
    {
        $this->tester->haveFixtures($fixtures);
    }

    /**
     * @param $name
     * @param int $id
     * @return mixed
     */
    public function grabFixture($name, $id = 1)
    {
        return $this->tester()->grabFixture($name, $id - 1);
    }

    /**
     * @return array
     */
    public function grabFixtures()
    {
        return $this->tester()->grabFixtures();
    }

    /**
     * @return UnitTester
     */
    public function tester(){
        return $this->tester;
    }
}