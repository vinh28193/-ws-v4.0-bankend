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


    /**
     * @return array
     */
    public function _fixtures()
    {
        return array_merge(parent::_fixtures(),[
            'store_additional_fee' => [
                'class' => 'common\fixtures\StoreAdditionalFeeFixture'
            ]
        ]);
    }

    public function testGetStoreManager(){
        $object = new AdditionalFeeObject();
        verify($object->getStoreManager())->isInstanceOf('common\components\StoreManager');
    }

    public function testGetStoreAdditionalFee(){
        $object = new AdditionalFeeObject();
        verify($object->getStoreAdditionalFee())->notNull();
    }

    public function testGetAdditionalFees(){
        $additionalFee = $this->make(AdditionalFeeObject::className(),[
            'getStoreAdditionalFee' => [
                'testFee' => new StoreAdditionalFee([

                ])
            ]
        ]);
        var_dump($additionalFee->getStoreAdditionalFee());die;
    }
}