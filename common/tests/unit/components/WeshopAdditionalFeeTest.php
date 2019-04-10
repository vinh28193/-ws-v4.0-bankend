<?php namespace common\tests\components;

use common\calculators\Calculator;
use common\components\AdditionalFeeCollection;
use common\models\Category;
use common\models\CategoryGroup;
use common\models\Store;
use common\models\StoreAdditionalFee;
use common\tests\stubs\AdditionalFeeObject;
use common\tests\UnitTestCase;
use yii\helpers\ArrayHelper;

class WeshopAdditionalFeeTest extends UnitTestCase
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

    private function loadCondition($name)
    {
        $conditions = require 'conditions.php';
        return isset($conditions[$name]) ? $conditions[$name] : null;
    }

    private function debugCondition($conditions, $additional)
    {
        foreach ($conditions as $condition) {
            $cal = new Calculator();
            $cal->register($condition);
            if ($cal->checkCondition($additional)) {
                echo "pass: {$cal->deception()} \n";
            } else {
                echo "not pass \n";
            }
        }
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

    private function mockAdditional($params = [])
    {
        $params = ArrayHelper::merge([
            'getExchangeRate' => function () {
                return 1000;
            },
            'getShippingQuantity' => 1
        ], $params);
        return $this->make(AdditionalFeeObject::className(), $params);
    }

//     tests
    public function testProductPriceOrigin()
    {
        $feeValue = rand(1, 9999);
        $store = new Store(['id' => 99]);
        $condition = null;
        $exRate = 23000;
        $quantity = rand(1, 100);
        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'product_price_origin',
        ]);
        $store->populateRelation('storeAdditionalFee', ['product_price_origin' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['product_price_origin'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);

        $collection->removeAll();

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity
        ]);

        $collection->withCondition($additional, 'product_price_origin', $feeValue);
        verify($collection->get('product_price_origin'))->notNull();
        verify(count($collection->get('product_price_origin', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('product_price_origin');
        verify($total)->equals([$feeValue * $quantity, $feeValue * $quantity * $exRate]);
    }

    public function testTaxFeeOrigin()
    {
        $feeValue = rand(1, 9999);
        $store = new Store(['id' => 99]);
        $condition = null;
        $exRate = 23000;
        $quantity = rand(1, 100);
        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'tax_fee_origin',
        ]);
        $store->populateRelation('storeAdditionalFee', ['tax_fee_origin' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['tax_fee_origin'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);

        $collection->removeAll();

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity
        ]);

        $collection->withCondition($additional, 'tax_fee_origin', $feeValue);
        verify($collection->get('tax_fee_origin'))->notNull();
        verify(count($collection->get('tax_fee_origin', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('tax_fee_origin');
        verify($total)->equals([$feeValue * $quantity, $feeValue * $quantity * $exRate]);
    }

    public function testOriginShippingFee()
    {
        $feeValue = rand(1, 9999);
        $store = new Store(['id' => 99]);
        $condition = null;
        $exRate = 23000;
        $quantity = rand(1, 100);
        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'origin_shipping_fee',
        ]);
        $store->populateRelation('storeAdditionalFee', ['origin_shipping_fee' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['tax_fee_origin'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);

        $collection->removeAll();

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity
        ]);

        $collection->withCondition($additional, 'origin_shipping_fee', $feeValue);
        verify($collection->get('origin_shipping_fee'))->notNull();
        verify(count($collection->get('origin_shipping_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('origin_shipping_fee');
        verify($total)->equals([$feeValue * $quantity, $feeValue * $quantity * $exRate]);
    }

    public function testWeshopFee()
    {
        $store = new Store(['id' => 99]);
        $condition = null;
        $exRate = 23000;
        $quantity = 1;
        /**
         *  < 450
         *      ebay : 12%
         *      amazon:10%
         *      other:10%
         *  450$ < 750$ : 10%
         *  750& < 1000$ : 9%
         *
         */
        $conditions = $this->loadCondition('weshop_fee');

        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'weshop_fee',
        ]);
        if ($conditions !== null) {
            $storeAdditionalFee->condition_data = json_encode($conditions);
        }
        $store->populateRelation('storeAdditionalFee', ['weshop_fee' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['weshop_fee'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);


        $this->tester->wantTo("case 1 < 450");
        // 12% ebay < 450
        $this->tester->wantTo("ebay < 450");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getItemType' => 'ebay',
            'getTotalOriginPrice' => 449
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([449 * 0.12 * $quantity, 449 * 0.12 * $quantity * $exRate]);

        // 10% amazon < 450
        $this->tester->wantTo("amazon < 450");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getItemType' => 'amazon',
            'getTotalOriginPrice' => 449
        ]);

//        $this->debugCondition($conditions,$additional);

        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([449 * 0.1 * $quantity, 449 * 0.1 * $quantity * $exRate]);
        // 10% other < 450
        $this->tester->wantTo("amazon < 450");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getItemType' => 'other',
            'getTotalOriginPrice' => 449
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([449 * 0.1 * $quantity, 449 * 0.1 * $quantity * $exRate]);

        // 450$ - 750$:10%
        $this->tester->wantTo("case 450$ - 750$:10%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 450
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([450 * 0.1 * $quantity, 450 * 0.1 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 749
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([749 * 0.1 * $quantity, 749 * 0.1 * $quantity * $exRate]);

        // 750$ - 1000$:9%
        $this->tester->wantTo("case 750$ - 1000$:9%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 750
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([750 * 0.09 * $quantity, 750 * 0.09 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 999
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([999 * 0.09 * $quantity, 999 * 0.09 * $quantity * $exRate]);

        // 1000$ - 1500$:8.5%
        $this->tester->wantTo("case 1000$ - 1500$:8.5%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 1000
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([1000 * 0.085 * $quantity, 1000 * 0.085 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 1499
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([1499 * 0.085 * $quantity, 1499 * 0.085 * $quantity * $exRate]);

        // 1500$ - 2000$:8%
        $this->tester->wantTo("case 1500$ - 2000$:8%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 1500
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([1500 * 0.08 * $quantity, 1500 * 0.08 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 1999
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([1999 * 0.08 * $quantity, 1999 * 0.08 * $quantity * $exRate]);


        // 2000$ - 2500$:7%
        $this->tester->wantTo("case 2000$ - 2500$:7%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 2000
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([2000 * 0.07 * $quantity, 2000 * 0.07 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 2499
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([2499 * 0.07 * $quantity, 2499 * 0.07 * $quantity * $exRate]);

        // 2500$ - 3000$:6%
        $this->tester->wantTo("case 2500$ - 3000$:6%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 2500
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([2500 * 0.06 * $quantity, 2500 * 0.06 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 2999
        ]);
//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([2999 * 0.06 * $quantity, 2999 * 0.06 * $quantity * $exRate]);


        // => 3000$:6%
        $this->tester->wantTo("case 2500$ - 3000$:6%");
        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 3000
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([3000 * 0.05 * $quantity, 3000 * 0.05 * $quantity * $exRate]);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 3001
        ]);

//        $this->debugCondition($conditions,$additional);
        $collection->removeAll();
        $collection->withCondition($additional, 'weshop_fee', null);
        verify($collection->get('weshop_fee'))->notNull();
        verify(count($collection->get('weshop_fee', [], false)))->equals(1);
        $total = $collection->getTotalAdditionFees('weshop_fee');
        verify($total)->equals([3001 * 0.05 * $quantity, 3001 * 0.05 * $quantity * $exRate]);
    }

    public function testIntlShippingFee()
    {
        $store = new Store(['id' => 99]);
        $condition = null;
        $exRate = 23000;
        $weight = 3;
        /**
         *  < 450
         *      ebay : 12%
         *      amazon:10%
         *      other:10%
         *  450$ < 750$ : 10%
         *  750& < 1000$ : 9%
         *
         */
        $conditions = $this->loadCondition('intl_shipping_fee');

        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'intl_shipping_fee',
        ]);
        if ($conditions !== null) {
            $storeAdditionalFee->condition_data = json_encode($conditions);
        }
        $store->populateRelation('storeAdditionalFee', ['intl_shipping_fee' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['intl_shipping_fee'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingWeight' => $weight,
            'getTotalOriginPrice' => 230,
        ]);
        $collection->removeAll();
        $collection->withCondition($additional, 'intl_shipping_fee', null);
        verify($collection->get('intl_shipping_fee'))->notNull();
        $total = $collection->getTotalAdditionFees('intl_shipping_fee');
        verify($total)->equals([10 * $weight, 10 * $weight * $exRate]);
    }


    public function testCustomFee()
    {
        $store = new Store(['id' => 99]);
        $exRate = 23000;
        $quantity = 2;

        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'custom_fee',
        ]);
        $store->populateRelation('storeAdditionalFee', ['custom_fee' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['weshop_fee'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);
        $collection->removeAll();

        $condition = $this->loadCondition('custom_fee');
        $categoryGroup = new CategoryGroup([
            'id' => 1000,
            'rule' => json_encode($condition),
        ]);
        $category = new Category([
            'id' => '999'
        ]);
        $category->populateRelation('categoryGroup', $categoryGroup);

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
            'getTotalOriginPrice' => 230,
            'getCustomCategory' => $category
        ]);
        $this->debugCondition($condition, $additional);

        $collection->withCondition($additional, 'custom_fee', null);
        verify($collection->get('custom_fee'))->notNull();
        $total = $collection->getTotalAdditionFees('custom_fee');
        verify($total)->equals([5 * $quantity, 5 * $quantity * $exRate]);
    }


    public function testDeliveryFeeLocal(){
        $store = new Store(['id' => 99]);
        $exRate = 23000;
        $quantity = 2;

        $storeAdditionalFee = new StoreAdditionalFee([
            'store_id' => $store->id,
            'name' => 'delivery_fee_local',
        ]);
        $conditions = $this->loadCondition('delivery_fee_local');

        if ($conditions !== null) {
            $storeAdditionalFee->condition_data = json_encode($conditions);
        }
        $store->populateRelation('storeAdditionalFee', ['delivery_fee_local' => $storeAdditionalFee]);
        $collection = $this->mockCollection($store->id, ['delivery_fee_local'], [
            'getStoreManager' => function () use ($store) {
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], false);
        $collection->removeAll();

        $condition = $this->loadCondition('delivery_fee_local');

        $additional = $this->mockAdditional([
            'getExchangeRate' => $exRate,
            'getShippingQuantity' => $quantity,
        ]);
        $this->debugCondition($condition, $additional);

        $collection->withCondition($additional, 'delivery_fee_local', null);
        verify($collection->get('delivery_fee_local'))->notNull();
        $total = $collection->getTotalAdditionFees('delivery_fee_local');
        verify($total)->equals([2 * $quantity, 2 * $quantity * $exRate]);
    }
}