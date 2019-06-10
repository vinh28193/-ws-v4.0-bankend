<?php


namespace frontend\modules\payment\models;

use common\components\AdditionalFeeTrait;
use common\components\AdditionalFeeInterface;
use common\models\Order as BaseOrder;

class Order extends BaseOrder implements AdditionalFeeInterface
{

    use AdditionalFeeTrait;

    public function getItemType()
    {
        return strtolower($this->portal);
    }

    public function getUser()
    {
        return parent::getUser();
    }

    public function getShippingQuantity()
    {
        return $this->total_quantity;
    }

    public function getShippingWeight()
    {
        return $this->total_weight_temporary;
    }

    public function getTotalOriginPrice()
    {
        return $this->total_price_amount_origin;
    }

    public function getIsNew()
    {
        return false;
    }

    public function getExchangeRate()
    {
        return $this->exchange_rate_fee;
    }

    public function getCustomCategory()
    {
        return parent::getCategory();
    }

    public function loadPaymentAdditionalFees()
    {
        $this->getAdditionalFees()->removeAll();
        foreach (['international_shipping_fee', 'purchase_fee'] as $name) {
            $this->getAdditionalFees()->withCondition($this, $name, null);
        }
    }
}