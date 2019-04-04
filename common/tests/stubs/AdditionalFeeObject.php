<?php


namespace common\tests\stubs;

use Yii;
use stdClass;
use common\components\AdditionalFeeInterface;
use common\components\StoreAdditionalFeeRegisterTrait;
use common\components\AdditionalFeeTrait;
use yii\base\BaseObject;

class AdditionalFeeObject extends BaseObject implements AdditionalFeeInterface
{
    use StoreAdditionalFeeRegisterTrait;
    use AdditionalFeeTrait;

    /**
     * @return string
     */
    public function getItemType()
    {
        return 'test';
    }

    public function getTotalOriginPrice()
    {
        return 99;
    }

    public function getCustomCategory()
    {
        $stdClass = new \stdClass();
        $stdClass->category = 9999;
        $stdClass->name = 'test';
    }

    public function getShippingWeight()
    {
        return 12;
    }

    public function getShippingQuantity()
    {
        return 2;
    }

    public function getIsForWholeSale()
    {
        return true;
    }

    public function getIsNew()
    {
        return true;
    }

    public function getExchangeRate()
    {
        return 1000;
    }
}