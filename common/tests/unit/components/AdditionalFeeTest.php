<?php

namespace common\tests\components;


use common\components\AdditionalFeeCollection;
use common\models\Product;
use common\models\ProductFee;
use Yii;
use yii\di\Instance;
use common\models\Store;
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
        return array_merge(parent::_fixtures(), [
            'store_additional_fee' => [
                'class' => 'common\fixtures\StoreAdditionalFeeFixture'
            ]
        ]);
    }

    public function testGetStoreManager()
    {
        $object = new AdditionalFeeObject();
        verify($object->getStoreManager())->isInstanceOf('common\components\StoreManager');
    }

    public function testGetStoreAdditionalFee()
    {
        $fees = ['test1', 'test2', 'test3'];
        $strs = implode(', ',$fees);
        $this->tester->comment("test with $strs");
        $additionalFee = $this->make(AdditionalFeeObject::className(), [
            'getStoreAdditionalFee' => function () use ($fees) {
                $result = [];
                foreach ($fees as $fee) {
                    $this->tester->comment("create a mocks additional fee with $fee");
                    $config = $this->make(StoreAdditionalFee::className(), [
                        'name' => $fee,
                    ]);
                    $result[$fee] = $config;
                }
                return $result;
            }
        ]);
        $storeFees = $additionalFee->getStoreAdditionalFee();
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee is not null");
            verify($storeFees[$fee])->notNull();
            $this->tester->wantTo("see store additional fee of $fee equals with $fee");
            verify($storeFees[$fee]->name)->equals($fee);
        }
        $store = new Store(['id' => 999]);
        $this->tester->comment("create fake Store with ID = {$store->id}");
        foreach ($fees as $fee) {
            $this->tester->comment("insert $fee in to database with name:$fee and store_id: {$store->id}");
            $this->tester->haveRecord(StoreAdditionalFee::className(), [
                'store_id' => $store->id,
                'name' => $fee
            ]);
        }
        $allRecord = [];
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee in database with saved data");
            $condition = [
                'store_id' => $store->id,
                'name' => $fee
            ];
            $this->tester->seeRecord(StoreAdditionalFee::className(),$condition );
            $allRecord[$fee] = $this->tester->grabRecord(StoreAdditionalFee::className(),$condition);
        }
        $this->tester->wantTo("populate relation `storeAdditionalFee` for store: {$store->id}");
        $store->populateRelation('storeAdditionalFee', $allRecord);
        $this->tester->comment("created mock additional fee with this relation");
        $additionalFee = $this->make(AdditionalFeeObject::className(), [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ]);

        verify($additionalFee->getStoreManager()->store->id)->equals($store->id);
        $storeFees = $additionalFee->getStoreAdditionalFee();
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee is not null");
            verify($storeFees[$fee])->notNull();
            $this->tester->wantTo("see {$storeFees[$fee]->name} is equals $fee");
            verify($storeFees[$fee]->name)->equals($fee);
            $this->tester->wantTo("see store of `{$storeFees[$fee]->name}` is equals with store {$store->id}");
            verify($storeFees[$fee]->store_id)->equals($store->id);
        }

    }

    public function testGetAdditionalFees(){

        $fees = ['test1', 'test2', 'test3'];
        $strs = implode(', ',$fees);
        $store = new Store(['id' => 999]);
        $this->tester->comment("create fake Store with ID = {$store->id}");
        foreach ($fees as $fee) {
            $this->tester->comment("insert $fee in to database with name:$fee and store_id: {$store->id}");
            $condition = [
                'store_id' => $store->id,
                'name' => $fee
            ];
            $this->tester->comment("clear up config for fee $fee");
            StoreAdditionalFee::deleteAll($condition); // todo with tester
            $this->tester->haveRecord(StoreAdditionalFee::className(), $condition);
        }
        $product = new Product(['id' => 9999]);
        $this->tester()->comment("create a product with id : {$product->id}");
        $this->tester()->wantTo("test get testGetAdditionalFees without load");
//        verify($product->getAdditionalFees()->toArray())->isEmpty();
        verify($product->getAdditionalFees()->count())->equals(0);
        $this->tester()->wantTo("test get testGetAdditionalFees without load");


        $this->tester->wantTo("test with $strs");
        foreach ($fees as $fee){
            $this->tester->comment("insert product {$product->id} in to database with fee :$fee");
            $condition = [
                'product_id' => $product->id,
                'name' => $fee
            ];
            $this->tester->comment("clear up product fee of product {$product->id}");
            ProductFee::deleteAll($condition);
            $this->tester->haveRecord(ProductFee::className(), $condition);
        }
        foreach ($fees as $fee){
            $this->tester->comment("insert product {$product->id} in to database with fee :$fee");
            $this->tester->haveRecord(ProductFee::className(), [
                'product_id' => $product->id,
                'name' => $fee
            ]);
        }
        $allRecord = [];
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee in database with saved data");
            $condition = [
                'product_id' => $product->id,
                'name' => $fee
            ];
            $this->tester->seeRecord(ProductFee::className(),$condition );
            $allRecord[$fee] = $this->tester->grabRecord(ProductFee::className(),$condition);
        }

        $addFee = $product->getAdditionalFees(true);
        var_dump($addFee);die;
        verify($addFee->count())->equals(count($fees));
        $productFees = $product->getAdditionalFees()->mget($fees);
        var_dump($productFees);die;
    }
}