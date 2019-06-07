<?php


namespace frontend\modules\payment;


use common\components\AdditionalFeeInterface;
use common\models\User;
use common\models\Category;
use yii\base\BaseObject;

class PaymentAdditionalFee extends BaseObject implements AdditionalFeeInterface
{
    public $itemType;
    public $totalOriginPrice;
    public $shippingWeight;
    public $shippingQuantity;
    public $user;
    public $exchangeRate;

    /**
     * @return string
     */
    public function getItemType()
    {
        return strtolower($this->itemType);
    }

    /**
     * @return integer
     */
    public function getTotalOriginPrice()
    {
        return $this->totalOriginPrice;
    }

    /**
     * @return null|Category
     */
    public function getCustomCategory()
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
        return $this->shippingWeight;
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
        return $this->shippingQuantity;

    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return false;
    }

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }
}