<?php namespace common\tests\helpers;

use common\tests\stubs\AdditionalFeeObject;
use common\tests\UnitTestCase;
use common\helpers\ObjectHelper;

class ObjectHelperTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    // tests
    public function testResolve()
    {

        $object = $this->make(AdditionalFeeObject::className(), [
            'getExchangeRate' => function () {
                return 1000;
            },
            'getShippingQuantity' => 1
        ]);
        verify(ObjectHelper::resolve($object,'getExchangeRate'))->equals(1000);
        verify(ObjectHelper::resolve($object,'getShippingQuantity'))->equals(1);
    }
}