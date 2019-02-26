<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 15:51
 */

namespace common\tests\_support;


class AdditionalFeeObject extends \yii\base\Model implements \common\components\AdditionalFeeInterface
{

    public $storeAdditionalFee;

    use \common\components\AdditionalFeeTrait;

    public $total_test_fee_local;


    public function attributes()
    {
        return ['total_test_fee_local'];
    }

    public function rules()
    {
        return [$this->attributes(), 'safe'];
    }

    public function init()
    {
        parent::init();
    }

    public function hasAttribute($name)
    {
        return in_array($name, $this->attributes());
    }

    public function getStoreAdditionalFee()
    {
       return $this->storeAdditionalFee;
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