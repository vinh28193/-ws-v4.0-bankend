<?php


namespace common\tests\stubs;

use common\components\AdditionalFeeInterface;
use common\components\AdditionalFeeTrait;
use common\models\User;
use yii\base\BaseObject;

class AdditionalFeeObject extends BaseObject implements AdditionalFeeInterface
{
    use AdditionalFeeTrait;
    /**
     * @return string
     */
    public function getItemType()
    {
    }

    /**
     * @return integer
     */
    public function getTotalOriginPrice()
    {
    }

    /**
     * @return \common\models\Category
     */
    public function getCustomCategory()
    {
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
    }

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
    }
}