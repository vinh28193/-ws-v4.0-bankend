<?php namespace common\tests\components;

use common\models\Product;
use common\models\ProductFee;
use common\models\Store;
use common\models\StoreAdditionalFee;
use common\tests\stubs\AdditionalFeeObject;
use common\tests\UnitTestCase;
use common\components\AdditionalFeeCollection;
use yii\helpers\ArrayHelper;

class AdditionalFeeCollectionTest extends UnitTestCase
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

    private function mockCollection($storeId, $fees, $params = [], $ensureDb = false)
    {
        $store = new Store(['id' => $storeId]);
        $this->tester->comment("create fake Store with ID = {$store->id}");
        $allRecord = [];
        if ($ensureDb) {
            StoreAdditionalFee::deleteAll(['store_id' => $store->id]);
        }
        foreach ((array)$fees as $fee) {
            $attribute = [
                'store_id' => $store->id,
                'name' => $fee
            ];
            if ($ensureDb) {
                $this->tester->haveRecord(StoreAdditionalFee::className(), $attribute);
                $this->tester->comment("insert $fee in to database with name:$fee and store_id: {$store->id}");
                $allRecord[$fee] = $this->tester->grabRecord(StoreAdditionalFee::className(), $attribute);
            } else {
                $config = $this->make(StoreAdditionalFee::className(), $attribute);
                $this->tester->comment("create a mock store additional fee with $fee");
                $allRecord[$fee] = $config;
            }
        }
        $this->tester->wantTo("populate relation `storeAdditionalFee` for store: {$store->id}");
        $this->tester->comment("created mock additional fee with this relation");
        $store->populateRelation('storeAdditionalFee', $allRecord);
        $params = ArrayHelper::merge([
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], $params);
        $collection = $this->make(AdditionalFeeCollection::className(), $params);

        return $collection;

    }

    public function testGetStoreManager()
    {
        $object = new AdditionalFeeCollection();
        verify($object->getStoreManager())->isInstanceOf('common\components\StoreManager');
    }


    public function testGetStoreAdditionalFee()
    {
        $fees = ['test1', 'test2', 'test3'];
        $strs = implode(', ', $fees);
        $this->tester->comment("test with $strs");
        $collection = $this->mockCollection(999, $fees);
        $storeFees = $collection->getStoreAdditionalFee();
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee is not null");
            verify($storeFees[$fee])->notNull();
            $this->tester->wantTo("see store additional fee of $fee equals with $fee");
            verify($storeFees[$fee]->name)->equals($fee);
        }
        $store = 1111;
        $collection = $this->mockCollection($store, $fees, [], true);
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee and store $store in database with saved data");
            $this->tester->seeRecord(StoreAdditionalFee::className(), [
                'store_id' => $store,
                'name' => $fee
            ]);
        }

        verify($collection->getStoreManager()->store->id)->equals($store);
        $storeFees = $collection->getStoreAdditionalFee();
        foreach ($fees as $fee) {
            $this->tester->wantTo("see $fee is not null");
            verify($storeFees[$fee])->notNull();
            $this->tester->wantTo("see {$storeFees[$fee]->name} is equals $fee");
            verify($storeFees[$fee]->name)->equals($fee);
            $this->tester->wantTo("see store of `{$storeFees[$fee]->name}` is equals with store $store");
            verify($storeFees[$fee]->store_id)->equals($store);
        }

    }

    public function testGetSetOwner()
    {
        $collection = new AdditionalFeeCollection();
        $owner = new AdditionalFeeObject();
        $collection->setOwner($owner);
        verify($collection->getOwner())->isInstanceOf(get_class($owner));
    }

    public function testLoadFormOwner()
    {
        $store = 123;
        $feeLists = ['feeA1', 'feeA2'];
        $product = new Product(['id' => 99999]);
        $this->tester()->comment("create a product with id : {$product->id}");
        $this->tester()->wantTo("test get testGetAdditionalFees without load");
//        verify($product->getAdditionalFees()->toArray())->isEmpty();
        $collection = $this->mockCollection($store, $feeLists);
        $this->tester()->wantTo("check before load");
        verify($collection->count())->equals(0);
        $this->tester()->comment("pre data for product : {$product->id}");
        ProductFee::deleteAll(['product_id' => $product->id]);
        foreach ($feeLists as $fee) {
            $this->tester->comment("insert product {$product->id} in to database with fee :$fee");
            $this->tester->comment("clear up product fee of product {$product->id}");
            $this->tester->haveRecord(ProductFee::className(), [
                'product_id' => $product->id,
                'type' => $fee
            ]);
        }
        $allRecord = [];
        foreach ($feeLists as $fee) {
            $this->tester->wantTo("see $fee in database with saved data");
            $condition = [
                'product_id' => $product->id,
                'type' => $fee
            ];
            $this->tester->seeRecord(ProductFee::className(), $condition);
            $allRecord[$fee] = $this->tester->grabRecord(ProductFee::className(), $condition);
        }
        $collection->loadFormOwner($product);
        verify($collection->count())->equals(count($feeLists));
        foreach ($feeLists as $fee) {
            $data = $collection->get($fee, null, true);
            verify($data)->notNull();
            verify($data['type'])->equals($fee);
        }
    }

    public function testKeys()
    {
        $keys = [];
        for ($i = 1; $i < 20; $i++) {
            $keys[] = "keyTest$i";
        }
        $collection = $this->mockCollection(100, $keys, [], false);
        $product = new Product(['id' => 99999]);
        ProductFee::deleteAll(['product_id' => $product->id]);
        foreach ($keys as $key) {
            $this->tester->comment("insert product {$product->id} in to database with fee :$key");
            $this->tester->comment("clear up product fee of product {$product->id}");
            $this->tester->haveRecord(ProductFee::className(), [
                'product_id' => $product->id,
                'type' => $key
            ]);
        }
        $collection->loadFormOwner($product);
        verify($collection->keys())->equals($keys);
        $collection->removeAll();
        foreach ($keys as $key) {
            $collection->add($key, ['test' => $key]);
        }
        verify($collection->keys())->equals($keys);
    }

    public function testMget()
    {
        $keys = [];
        for ($i = 1; $i < 20; $i++) {
            $keys[] = "keyTest$i";
        }
        $collection = $this->mockCollection(100, $keys, [], false);
        verify(array_keys($collection->mget()))->equals($keys);
        $keyGet = ['keyTest1','keyTest1'];
        verify(array_keys($collection->mget($keyGet)))->equals($keyGet);
    }
}