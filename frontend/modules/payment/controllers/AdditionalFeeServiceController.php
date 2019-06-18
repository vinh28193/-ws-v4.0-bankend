<?php


namespace frontend\modules\payment\controllers;

use common\boxme\InternationalShippingCalculator;
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
        $bodyParams = $this->request->bodyParams;
        if (($wh = $this->getPickUpWareHouse()) === false) {
            $this->response(false, "can not get pickup warehouse");
        }
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();
        if (($mapping = $shippingForm->getReceiverMapping()) === false) {
            $this->response(false, "not found mapping for {$shippingForm->getReceiverDistrictName()}");
        }
        if (($pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id')) === null) {
            $this->response(false, "can not resolve pick up id");
        }
        if (($userId = ArrayHelper::getValue($wh, 'ref_user_id')) === null) {
            $this->response(false, "can not resolve user id");
        }
        $from = [
            'country' => 'US',
            'pickup_id' => $pickUpId
        ];
        $to = [
            'contact_name' => $shippingForm->receiver_name,
            'company_name' => '',
            'email' => $shippingForm->receiver_email,
            'address' => $shippingForm->receiver_address,
            'address2' => '',
            'phone' => $shippingForm->receiver_phone,
            'phone2' => '',
            'country' => $this->storeManager->store->country_code,
            'province' => $mapping['province'],
            'district' => $mapping['district'],
            'zipcode' => $shippingForm->receiver_post_code,
            'tax_id' => '',
        ];
        $shipment = [
            'content' => '',
            'total_parcel' => count($payment->getOrders()),
            'total_amount' => $payment->total_order_amount,
            'description' => '',
            'amz_shipment_id' => '',
        ];
        $parcels = [];
        $chargeable_weight = 0;
        foreach ($payment->getOrders() as $order) {
            $parcel = [];
            $parcel['weight'] = WeshopHelper::roundNumber((int)$order->total_weight_temporary * 1000);
            $chargeable_weight += WeshopHelper::roundNumber((int)$order->total_weight_temporary * 1000);
            $parcel['amount'] = WeshopHelper::roundNumber($order->total_amount_local);
            $parcel['description'] = "order of seller `{$order->seller->seller_name}`";
            $its = [];
            foreach ($order->products as $product) {
                $its[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber((int)$order->total_weight_temporary * 1000),
                    'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'customs_value' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'quantity' => $product->quantity_customer,
                ];
            }
            $parcel['items'] = $its;
            $parcels[] = $parcel;
        }
        $shipment['parcels'] = $parcels;
        $shipment['chargeable_weight'] = $chargeable_weight;
        $config = [
            'preview' => 'Y',
            'return_mode' => 0,
            'insurance' => 'N',
            'document' => 0,
            'currency' => $this->storeManager->store->currency,
            'unit_metric' => 'metric',
            'sort_mode' => 'best_rating',
            'auto_approve' => 'Y',
            'create_by' => 0,
            'create_from' => 'create_order_netsale',
            'order_type' => 'dropship',
            'check_stock' => 'N',
        ];
        $params = [
            'ship_from' => $from,
            'ship_to' => $to,
            'shipments' => $shipment,
            'config' => $config,
            'payment' => [
                'cod_amount' => 0,
                'fee_paid_by' => 'sender'
            ],
            'referral' => [
                'order_number' => '',
                'coupon_code' => ''
            ]
        ];
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info($params, "total calculator time : $time s");
        $calculator = new InternationalShippingCalculator();
        $response = $calculator->CalculateFee($params, $userId, $this->storeManager->store->country_code);
        list($success, $data) = $response;
        if ($success) {
            return $this->response(true, 'Calculator success', $data);
        }
        return $this->response(false, $data);
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