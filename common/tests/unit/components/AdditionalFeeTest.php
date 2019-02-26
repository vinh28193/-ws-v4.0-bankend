<?php namespace common\tests\components;

use Codeception\Test\Unit;
use common\components\StoreManager;
use common\tests\_support\ActiveStore;
use common\tests\_support\AdditionalFeeObject;
use common\tests\_support\TestCondition;
use common\tests\_support\StoreAdditionalFee;

class AdditionalFeeTest extends Unit
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
        $additionalFeeTest = new AdditionalFeeObject();
        $class = $additionalFeeTest->getStoreManager();
        $class = get_class($class);
        $this->assertEquals($class, StoreManager::className());
    }

    public function testSetAdditionalFees()
    {
        $additionalFeeTest = new AdditionalFeeObject();
        $condition = new TestCondition();
        $storeAdditionalFee = new StoreAdditionalFee();
        $storeAdditionalFee->name = 'test_fee';
        $storeAdditionalFee->condition_name = $condition->name;
        $storeAdditionalFee->condition_data = serialize($condition);
        $storeAdditionalFee->is_convert = 1;
        $additionalFeeTest->storeAdditionalFee['test_fee'] = $storeAdditionalFee;
        $fee = ['test_fee' => 999];
        $withCondition = true;
        $ensureReadOnly = false;

        $additionalFeeTest->setAdditionalFees($fee, $withCondition, $ensureReadOnly);
        $this->assertEquals($additionalFeeTest->total_test_fee_local, 999 * $additionalFeeTest->getExchangeRate());
    }
}