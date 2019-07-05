<?php


namespace frontend\modules\payment\controllers;

use common\components\InternationalShippingCalculator;
use common\components\GetUserIdentityTrait;
use common\helpers\WeshopHelper;
use common\models\Address;
use common\products\BaseProduct;
use frontend\modules\payment\models\Order;
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
            return $this->response(false, "can not get pickup warehouse");
        }
        $isId = $store->country_code === 'ID';
        $paymentParams = $bodyParams['payment'];
        if (($orderParams = ArrayHelper::remove($paymentParams, 'orders')) === null) {
            return $this->response(false, "can not get form null orders");
        }

        $payment = new Payment($paymentParams);
        $orders = [];
        foreach ($orderParams as $orderParam) {
            if (isset($orderParam['totalFinalAmount'])) {
                unset($orderParam['totalFinalAmount']);
            }
            if (isset($orderParam['totalAmountLocal'])) {
                unset($orderParam['totalAmountLocal']);
            }
            $order = new Order($orderParam);
            if ($order->cartId !== null && $order->createOrderFromCart() !== false) {
                $orders[$order->cartId] = $order;
            } else if ($order->ordercode !== null && ($order = Order::findOne(['ordercode' => $order->ordercode])) !== null) {
                $orders[$order->ordercode] = $order;
            }
        }
        $payment->setOrders($orders);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();
        $ship_to = [
            'contact_name' => 'ws calculator',
            'company_name' => '',
            'email' => '',
            'address' => 'ws auto',
            'address2' => '',
            'phone' => '0987654321',
            'phone2' => '',
            'province' => $shippingForm->receiver_province_id,
            'district' => $shippingForm->receiver_district_id,
            'country' => $store->country_code,
            'zipcode' => $shippingForm->receiver_post_code,
        ];
        // lấy theo buyer
        if ($shippingForm->other_receiver !== false) {
            // nếu mà là địa chỉ chọn
            if ((int)$shippingForm->enable_buyer === 1) {
                if ($shippingForm->buyer_name !== null) {
                    $ship_to['contact_name'] = $shippingForm->buyer_name;
                }
                if ($shippingForm->buyer_address !== null) {
                    $ship_to['address'] = $shippingForm->buyer_address;
                }
                if ($shippingForm->buyer_phone !== null) {
                    $ship_to['phone'] = $shippingForm->buyer_phone;
                }
                $ship_to['province'] = $shippingForm->buyer_province_id;
                $ship_to['district'] = $shippingForm->buyer_district_id;
                $ship_to['zipcode'] = $shippingForm->buyer_post_code;
            } else if ($shippingForm->buyer_address_id !== null && ($buyer = Address::findOne($shippingForm->buyer_address_id)) !== null) {
                $ship_to['contact_name'] = implode(' ', [$buyer->first_name, $buyer->last_name]);
                $ship_to['address'] = $buyer->address;
                $ship_to['phone'] = $buyer->phone;
                $ship_to['province'] = $buyer->province_id;
                $ship_to['district'] = $buyer->district_id;
                $ship_to['zipcode'] = $store->country_code === 'ID' ? $buyer->post_code : '';
            }
        } else {
            if ((int)$shippingForm->enable_receiver === 1) {
                if ($shippingForm->receiver_name !== null) {
                    $ship_to['contact_name'] = $shippingForm->receiver_name;
                }
                if ($shippingForm->receiver_address !== null) {
                    $ship_to['address'] = $shippingForm->receiver_address;
                }
                if ($shippingForm->receiver_phone !== null) {
                    $ship_to['phone'] = $shippingForm->receiver_phone;
                }

                $ship_to['province'] = $shippingForm->receiver_province_id;
                $ship_to['district'] = $shippingForm->receiver_district_id;
                $ship_to['zipcode'] = $shippingForm->receiver_post_code;
            } else if ($shippingForm->receiver_address_id !== null && ($receiver = Address::findOne($shippingForm->receiver_address_id)) !== null) {
                $ship_to['contact_name'] = implode(' ', [$receiver->first_name, $receiver->last_name]);
                $ship_to['address'] = $receiver->address;
                $ship_to['phone'] = $receiver->phone;
                $ship_to['province'] = $receiver->province_id;
                $ship_to['district'] = $receiver->district_id;
                $ship_to['zipcode'] = $store->country_code === 'ID' ? $receiver->post_code : '';
            }
        }
        if (($pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id')) === null) {
            $this->response(false, "can not resolve pick up id");
        }
        if (($userId = ArrayHelper::getValue($wh, 'ref_user_id')) === null) {
            $this->response(false, "can not resolve user id");
        }
        $results = [];
        foreach ($payment->getOrders() as $order) {
            $weight = $order->total_weight_temporary * 1000;
            $totalAmount = $order->total_amount_local;
            $items = [];
            foreach ($order->products as $product) {
                $items[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber(($weight / $product->quantity_customer)),
                    'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
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
                'ship_to' => $ship_to,
                'shipments' => [
                    'content' => '',
                    'total_parcel' => 1,
                    'total_amount' => $totalAmount,
                    'description' => '',
                    'amz_shipment_id' => '',
                    'chargeable_weight' => $weight,
                    'parcels' => [$parcel]
                ],
            ];

            $location = InternationalShippingCalculator::LOCATION_AMAZON;
            if (strtoupper($order->type) === BaseProduct::TYPE_EBAY) {
                $location = InternationalShippingCalculator::LOCATION_EBAY_US;
                if (strtoupper($order->seller->country_code) !== 'US') {
                    $location = InternationalShippingCalculator::LOCATION_EBAY;
                }
            }
            $calculator = new InternationalShippingCalculator();
            $response = $calculator->CalculateFee($params, $userId, $store->country_code, $store->currency, $location);
            $response = array_combine(['success', 'couriers'], $response);
            $results[$order->cartId] = $response;
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