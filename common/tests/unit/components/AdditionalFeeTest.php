<?php
namespace common\tests\components;

use Yii;
use yii\di\Instance;
use common\models\StoreAdditionalFee;
use common\tests\UnitTestCase;
use common\tests\stubs\AdditionalFeeObject;

class AdditionalFeeTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testGetStoreManager(){
        $object = new AdditionalFeeObject();
        verify($object->getStoreManager())->isInstanceOf('common\components\StoreManager');
    }

    public function testGetStoreAdditionalFee(){
        $object = new AdditionalFeeObject();
        verify($object->getStoreAdditionalFee())->notEmpty();
    }

    public function testGetAdditionalFees(){
        $additionalFee = $this->make(AdditionalFeeObject::className(),[
            'getStoreAdditionalFee' => [
                'testFee' => new StoreAdditionalFee([

                ])
            ]
        ]);
    }
}