<?php namespace common\tests\unit;

use common\tests\UnitTestCase;

class FristUnitTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        var_dump($this->grabFixtures());die;
    }
}