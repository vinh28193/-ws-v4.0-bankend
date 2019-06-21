<?php


namespace frontend\modules\payment\controllers;

use common\components\InternationalShippingCalculator;
use common\components\GetUserIdentityTrait;
use common\helpers\WeshopHelper;
use common\models\Address;
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
        if (!array_key_exists("payment",$bodyParams)) {  Yii::info('Not exit data payment in bodyParams'); die; }
        $payment = new Payment($bodyParams['payment']);
        if (!array_key_exists("shipping",$bodyParams)) {  Yii::info('Not exit data shipping in bodyParams'); die; }
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();
        $ship_to = [
            'contact_name' => $shippingForm->receiver_name,
            'company_name' => '',
            'email' => '',
            'address' => $shippingForm->receiver_address,
            'address2' => '',
            'phone' => $shippingForm->receiver_phone,
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
                $ship_to['contact_name'] = $shippingForm->buyer_name;
                $ship_to['address'] = $shippingForm->buyer_address;
                $ship_to['phone'] = $shippingForm->buyer_phone;
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
                $ship_to['contact_name'] = $shippingForm->receiver_name;
                $ship_to['address'] = $shippingForm->receiver_address;
                $ship_to['phone'] = $shippingForm->receiver_phone;
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
            $weight = WeshopHelper::roundNumber((int)$order->total_weight_temporary * 1000);
            if($weight <=0) {
                Yii::info("Lỗi nghiêm trọng Cân nặng bằng hoặc nhỏ hơn 0");
                Yii::error("Lỗi nghiêm trọng Cân nặng bằng hoặc nhỏ hơn 0",$order);
            }

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
            $calculator = new InternationalShippingCalculator();
            $response = $calculator->CalculateFee($params, $userId, $store->country_code, $store->currency);
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
