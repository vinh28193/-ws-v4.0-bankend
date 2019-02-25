<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 15:51
 */

namespace common\tests\_support;


class AdditionalFeeObject extends \yii\base\BaseObject implements \common\components\AdditionalFeeInterface
{


    use \common\components\AdditionalFeeTrait;

    public $total_test_fee_local;


    public function init()
    {
        parent::init();
    }

    public function getStoreAdditionalFee()
    {
        $condition = new TestCondition();
        $storeAdditionalFee = new StoreAdditionalFee();
        $storeAdditionalFee->name = 'test_fee';
        $storeAdditionalFee->condition_name = $condition->name;
        $storeAdditionalFee->condition_data = serialize($condition);
        return ['test_fee' => [$storeAdditionalFee]];
    }

    public function getStoreManager()
    {
        $storeManager = new \common\components\StoreManager();
        $storeManager->storeClass = ActiveStore::className();
        return $storeManager;
    }

    public function getItemType()
    {
        return 'test';
    }

    public function getTotalOriginPrice()
    {
        return 12345;
    }

    public function getCustomCategory()
    {
        $std = new \stdClass();
        $std->interShippingB = 123;
        return $std;
    }

    public function getIsForWholeSale()
    {
        return false;
    }

    public function getShippingWeight()
    {
        return 12;
    }

    public function getShippingQuantity()
    {
        return 2;
    }

    public function getExchangeRate()
    {
        return 32000;
    }
}