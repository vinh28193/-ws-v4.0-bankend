<?php


namespace frontend\modules\payment\controllers;

use common\components\InternationalShippingCalculator;
use common\components\GetUserIdentityTrait;
use common\helpers\WeshopHelper;
use frontend\modules\payment\models\ShippingForm;
use frontend\modules\payment\Payment;
use Yii;
use yii\helpers\ArrayHelper;

class AdditionalFeeServiceController extends BasePaymentController
{

    use GetUserIdentityTrait;

    public function actionCourierCalculator()
    {
        $start = microtime(true);
        $store = $this->storeManager->store;
        $bodyParams = $this->request->bodyParams;
        if (($wh = $this->getPickUpWareHouse()) === false) {
            $this->response(false, "can not get pickup warehouse");
        }
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();
        if (($pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id')) === null) {
            $this->response(false, "can not resolve pick up id");
        }
        if (($userId = ArrayHelper::getValue($wh, 'ref_user_id')) === null) {
            $this->response(false, "can not resolve user id");
        }
        $results = [];

        foreach ($payment->getOrders() as $order) {
            $weight = WeshopHelper::roundNumber((int)$order->total_weight_temporary * 1000);
            $totalAmount = $order->total_amount_local;
            $items = [];
            foreach ($order->products as $product) {
                $items[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber((int)$product->total_weight_temporary * 1000),
                    'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'customs_value' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'quantity' => $product->quantity_customer,
                ];
            }
            $parcel = [
                'weight' => $weight,
                'amount' => $totalAmount,
                'description' => $order->seller ? "order of seller `{$order->seller->seller_name}`" : "",
                'items' => $items
            ];
            $params = [
                'ship_from' => [
                    'country' => 'US',
                    'pickup_id' => $pickUpId
                ],
                'ship_to' => [
                    'contact_name' => $shippingForm->receiver_name,
                    'company_name' => '',
                    'email' => '',
                    'address' => $shippingForm->receiver_address,
                    'address2' => '',
                    'phone' => $shippingForm->receiver_phone,
                    'phone2' => '',
                    'country' => $store->country_code,
                    'province' => $shippingForm->receiver_province_id,
                    'district' => $shippingForm->receiver_province_id,
                    'zipcode' => $shippingForm->receiver_post_code,
                    'tax_id' => '',
                ],
                'shipments' => [
                    'content' => '',
                    'total_parcel' => 1,
                    'total_amount' => $totalAmount,
                    'description' => '',
                    'amz_shipment_id' => '',
                    'chargeable_weight' => $weight,
                    'parcels' => $parcel
                ],
            ];
            $calculator = new InternationalShippingCalculator();
            $response = $calculator->CalculateFee($params, $userId, $store->country_code, $store->currency);
            $results[$order->getUniqueCode()] = array_combine(['success', 'couriers'], $response);
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        return $this->response(true, "total calculator time : $time s", $results);
    }

    public function getPickUpWareHouse()
    {
        if (($user = $this->getUser()) !== null && $user->getPickupWarehouse() !== null) {
            return $user->getPickupWarehouse();
        }
        if (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) === null) {
            return false;
        }
        $current = $params['default'];
        return ArrayHelper::getValue($params, "warehouses.$current", false);
    }
}