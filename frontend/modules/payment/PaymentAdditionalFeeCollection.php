<?php


namespace frontend\modules\payment;


use common\components\AdditionalFeeCollection;

class PaymentAdditionalFeeCollection extends AdditionalFeeCollection
{

    public function loadOrder($order, $user, $exRate)
    {
        $additionalFee = new PaymentAdditionalFee();
        $additionalFee->itemType = $order['portal'];
        $additionalFee->totalOriginPrice = $order['total_price_amount_origin'];
        $additionalFee->shippingWeight = $order['total_weight_temporary'];
        $additionalFee->shippingQuantity = $order['total_quantity'];
        $additionalFee->user = $user;
        $additionalFee->exchangeRate = $exRate;
        foreach (['intl_shipping_fee', 'weshop_fee'] as $name) {
            $this->withCondition($additionalFee, $name, null);
        }
    }
}