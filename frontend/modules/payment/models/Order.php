<?php


namespace frontend\modules\payment\models;

use common\components\AdditionalFeeInterface;
use common\components\AdditionalFeeTrait;
use common\models\Order as BaseOrder;
use common\models\User;

class Order extends BaseOrder implements AdditionalFeeInterface
{

    public $cartId;

    use AdditionalFeeTrait;

    public function getType()
    {
        return strtolower($this->portal);
    }

    public function getUserLevel()
    {
        if ($this->user === null) {
            return User::LEVEL_NORMAL;
        }
        return $this->user->getUserLevel();
    }

    public function getShippingQuantity()
    {
        return $this->total_quantity;
    }

    public function getShippingWeight()
    {
        return $this->total_weight_temporary;
    }

    public function getTotalOrigin()
    {
        return $this->total_price_amount_origin;
    }

    public function getIsNew()
    {
        return false;
    }

    public function getIsSpecial()
    {
        return false;
    }

    public function getExchangeRate()
    {
        return $this->exchange_rate_fee;
    }

    public function getCategory()
    {
        return parent::getCategory();
    }

    public function getShippingFrom()
    {
        return null;
    }

    public function getShippingTo()
    {
        return null;
    }

    public function getShippingParcel()
    {
        return null;
    }


    public function loadPaymentAdditionalFees()
    {
        $this->getAdditionalFees()->removeAll();
        foreach (['international_shipping_fee', 'purchase_fee'] as $name) {
            $this->getAdditionalFees()->withCondition($this, $name, null);
        }
    }
}