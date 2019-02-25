<?php namespace common\tests\components;

use common\tests\_support\AdditionalFeeObject;

class AdditionalFeeTest extends \Codeception\Test\Unit
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

    public function testGetStoreManager(){
        $this->tester->am('tester');
    }
}