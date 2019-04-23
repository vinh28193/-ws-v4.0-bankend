<?php


namespace common\boxme;


class BoxmeClient extends BaseClient
{


    /**
     * Create Order Domestic
     * @param $params
     * @return bool|mixed
     */
    public function createOrder($params)
    {
        return $this->createRequest('courier/pricing/calculate', $params);
    }

    /**
     * @param $params
     * @return bool|mixed
     */
    public function pricingCalculate($params)
    {
        return $this->createRequest('courier/pricing/create_order', $params);
    }

    /**
     * @param $orderCode
     * @return bool|mixed
     */
    public function cancelOrder($orderCode)
    {
        return $this->createRequest("orders/cancel/$orderCode/", []);
    }

    /**
     * @param $orderCode
     * @param string $note
     * @return bool|mixed
     */
    public function returnOrder($orderCode, $note = 'Reason for return order')
    {
        return $this->createRequest('courier/intergrate/confirm_return_tracking_code', [
            'tracking_code' => $orderCode,
            'note' => $note
        ]);
    }

    /**
     * @param $orderCode
     * @param string $note
     * @return bool|mixed
     */
    public function reshipOrder($orderCode, $note = 'Reason for reshipping  order')
    {
        return $this->createRequest('courier/intergrate/confirm_delivery_tracking_code', [
            'tracking_code' => $orderCode,
            'note' => $note
        ]);
    }

    /**
     * @param $params
     * @return bool|mixed
     */
    public function shippingLabel($params)
    {
        return $this->createRequest('courier/intergrate/confirm_delivery_tracking_code', $params);
    }
}