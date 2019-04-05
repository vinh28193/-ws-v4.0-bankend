<?php namespace common\tests\components;

use common\models\Product;
use common\models\ProductFee;
use common\models\Store;
use common\models\StoreAdditionalFee;
use common\tests\stubs\AdditionalFeeObject;
use common\tests\stubs\TestCondition;
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
        verify($collection->keys())->equals([]);
        foreach ($keys as $key) {
            $collection->add($key, ['test' => $key]);
        }
        verify($collection->keys())->equals($keys);
    }

    public function testMsetMget()
    {
        $keys = [];
        for ($i = 1; $i < 20; $i++) {
            $key = "keyTest$i";
            foreach ([1, 2, 3, 4] as $num) {
                $keys[$key][] = [
                    'type' => $key,
                    'name' => $key,
                    'amount' => $num,
                ];
            }
        }
        $collection = $this->mockCollection(100, array_keys($keys), [], false);
        $collection->mset($keys);
        verify($collection->mget())->equals($keys);
        $keyGet = ['keyTest1', 'keyTest9', 'keyTest11'];
        verify(array_keys($collection->mget($keyGet)))->equals($keyGet);
        $newKey = [];
        foreach (array_keys($keys) as $key) {
            if (in_array($key, $keyGet)) {
                continue;
            }
            $newKey[] = $key;
        }
        $mgets = $collection->mget(null, $keyGet);
        foreach ($mgets as $mkey => $mvalue) {
            verify($keys[$mkey])->equals($mvalue);
            foreach ($keyGet as $except) {
                verify($except)->notEquals($mkey);
            }
        }
    }


    public function testWithCondition()
    {
        $store = new Store(['id' => 99]);
        $feeName = 'test';
        $condition = new TestCondition();
        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'test',
            'label' => 'fee test',
            'is_convert' => 1,
            'condition_name' => $condition->name,
            'condition_data' => serialize($condition),
            'currency' => 'test'
        ]);
        $store->populateRelation('storeAdditionalFee', [$feeName => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, [$feeName], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);
        $owner = $this->make(AdditionalFeeObject::className(), [
            'getExchangeRate' => function () {
                return 1000;
            }
        ]);
        $collection->withCondition($owner, $feeName, 123);

        verify($collection->get($feeName))->equals([
            'type' => $storeAdditionalFee->name,
            'name' => $storeAdditionalFee->label,
            'amount' => 123,
            'local_amount' => 123000,
            'discount_amount' => 0,
            'currency' => $storeAdditionalFee->currency
        ]);
    }

    public function testWithConditions()
    {
        $store = new Store(['id' => 99]);
        $exRate = 1000;
        $results = [];
        $params = [];
        $fees = ['test1' => 123, 'test2' => 234];
        foreach ($fees as $name => $amount) {
            $condition = new TestCondition();
            $config = new StoreAdditionalFee([
                'store_id' => $store->id,
                'name' => $name,
                'label' => $name,
                'is_convert' => 1,
                'condition_name' => $condition->name,
                'condition_data' => serialize($condition),
                'currency' => $name
            ]);
            $results[$name] = $config;
            $params[$name] = [
                'type' => $config->name,
                'name' => $config->label,
                'amount' => $amount,
                'local_amount' => $amount * $exRate,
                'discount_amount' => 0,
                'currency' => $config->currency
            ];
        }
        $store->populateRelation('storeAdditionalFee', $results);
        $collection = $this->mockCollection($store->id, array_keys($fees), [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);
        $owner = $this->make(AdditionalFeeObject::className(), [
            'getExchangeRate' => function () use ($exRate) {
                return $exRate;
            }
        ]);
        $collection->withConditions($owner, $fees, false);
        verify($collection->count())->equals(count($fees));
        foreach (array_keys($fees) as $fee) {
            if (isset($params[$fee])) {
                verify($collection->get($fee))->equals($params[$fee]);
            }
        }
    }

    public function testCreateItem()
    {
        $exRate = 1000;
        $condition = new TestCondition();
        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => 999,
            'name' => 'test',
            'label' => 'fee test',
            'condition_name' => $condition->name,
            'condition_data' => serialize($condition),
        ]);
        $owner = $this->make(AdditionalFeeObject::className(), [
            'getExchangeRate' => function () use ($exRate) {
                return $exRate;
            }
        ]);
        $collection = $this->mockCollection(999, [], [], false);
        $item = $collection->createItem($storeAdditionalFee, $owner, 15, 8, 'vnd');
        verify($item)->equals([
            'type' => $storeAdditionalFee->name,
            'name' => $storeAdditionalFee->label,
            'amount' => 15,
            'local_amount' => 15,
            'discount_amount' => 8,
            'currency' => 'vnd'
        ]);
    }

    public function testHasStoreAdditionalFeeByKey(){
        $feeKey = 'test';
        $collection = $this->mockCollection(9999,[$feeKey]);
        verify($collection->hasStoreAdditionalFeeByKey($feeKey))->true();
    }

    public function testGetStoreAdditionalFeeByKey(){
        $feeKey = 'test';
        $collection = $this->mockCollection(9999,[$feeKey]);
        verify($collection->getStoreAdditionalFeeByKey($feeKey))->notNull();
        verify($collection->getStoreAdditionalFeeByKey($feeKey))->isInstanceOf(StoreAdditionalFee::className());
        verify($collection->getStoreAdditionalFeeByKey('no_key'))->null();
    }

    /**
     * ham xem commnet tren ham
     */
    public function testGetTotalAdditionFees(){

    }
}