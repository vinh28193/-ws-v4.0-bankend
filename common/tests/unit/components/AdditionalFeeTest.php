<?php

namespace common\tests\components;


use common\components\AdditionalFeeCollection;
use common\tests\UnitTestCase;
use common\tests\stubs\AdditionalFeeObject;


class AdditionalFeeTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;



    public function testGetAdditionalFees(){
        $object = $this->make(AdditionalFeeObject::className());
        verify($object->getAdditionalFees())->isInstanceOf(AdditionalFeeCollection::className());
        verify($object->getAdditionalFees())->isInstanceOf('IteratorAggregate');
        verify($object->getAdditionalFees())->isInstanceOf('ArrayAccess');
        verify($object->getAdditionalFees())->isInstanceOf('Countable');

    }

}