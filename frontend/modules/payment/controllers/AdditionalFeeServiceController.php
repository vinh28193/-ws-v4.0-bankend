<?php


namespace frontend\modules\payment\controllers;

use common\boxme\InternationalShippingCalculator;
use frontend\modules\payment\models\ShippingForm;
use frontend\modules\payment\Payment;
use Yii;
use yii\helpers\ArrayHelper;

class AdditionalFeeServiceController extends BasePaymentController
{

    public function actionCourierCalculator()
    {
        $start = microtime(true);
        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams['payment']);
        $shippingForm = new ShippingForm($bodyParams['shipping']);
        $shippingForm->ensureReceiver();
        $from = [
            "country" => "US",
            "pickup_id" => 35549
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
            'province' => 1,
            'district' => 7,
            'zipcode' => '',
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
            $parcel['weight'] = $order->total_weight_temporary;
            $chargeable_weight += (int)$order->total_weight_temporary * 1000;
            $parcel['amount'] = $order->total_amount_local;
            $parcel['description'] = "order of seller {$order->seller->seller_name}";
            $its = [];
            foreach ($order->products as $product) {
                $its[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => $product->total_weight_temporary * 1000,
                    'amount' => $product->total_price_amount_local,
                    'customs_value' => $product->total_price_amount_local,
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
            'return_mode' => '0',
            'insurance' => 'N',
            'document' => '0',
            'currency' => 'VND',
            'unit_metric' => 'metric',
            'sort_mode' => 'best_rating',
            'auto_approve' => 'Y',
            'create_by' => '0',
            'create_from' => 'create_order_netsale',
            'order_type' => 'dropship',
            'check_stock' => 'N',
        ];
        $params = [
            'from' => $from,
            'to' => $to,
            'shipment' => $shipment,
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
        $response =  $calculator->CalculateFee($params, 23, 'VN');
        return $this->response($response[0],'', $response[1]);
    }
}