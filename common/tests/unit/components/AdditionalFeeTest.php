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