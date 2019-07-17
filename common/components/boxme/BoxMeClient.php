<?php
namespace common\components\boxme;
use common\components\lib\TextUtility;
use common\components\StoreManager;
use common\components\ThirdPartyLogs;
use common\helpers\WeshopHelper;
use common\models\boxme\ConfigForm;
use common\models\boxme\ShipToForm;
use common\models\boxme\ShipmentForm;
use common\models\Order;
use common\models\Product;
use common\models\SystemCountry;
use common\models\User;
use Courier\CourierClient;
use Courier\CreateOrderRequest;
use Courier\CreateOrderResponse;
use linslin\yii2\curl\Curl;
use Seller\CreateShipmentRequest;
use Seller\CreateShipmentResponse;
use Seller\SellerClient;
use Seller\SyncProductRequest;
use Seller\SyncProductResponse;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 15:09
 */

class BoxMeClient
{
    /**
     * @param ShipToForm $ship_from
     * @param ShipToForm $ship_to
     * @param ShipmentForm $shipment
     * @param ConfigForm $config
     * @return array|mixed|\yii\httpclient\Response|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function getCourierApiBoxMe($ship_from ,$ship_to,$shipment,$config){
        $params['ship_from'] = (array)$ship_from;
        if(!$ship_from->zipcode)
            unset($params['ship_from']['zipcode']);

        $params['ship_to'] = (array)$ship_to;
        unset($params['ship_to']['pickup_id']);
        if(!$ship_to->zipcode)
            unset($params['ship_to']['zipcode']);
        $params['shipments'] = (array)$shipment;
        unset($params['shipments']['cod_amount']);

        $params['config'] = (array)$config;

        $params['payment']['cod_amount']= $shipment->cod_amount;
        $params['referral']['order_number']= '';

        $token = Yii::$app->params['boxme'][$ship_to->country_id == 1 ? 'vn' : 'id']['TOKEN'];
        $url = Yii::$app->params['boxme'][$ship_to->country_id == 1 ? 'vn' : 'id']['URL'].'v1/courier/pricing/calculate';
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->addHeaders([
            'Authorization' => "Token $token"
        ]);
        $request->setFormat('json');
        $request->setMethod('POST');
        $request->setData($params);
        $response = $client->send($request);
        $params["token"] = $token;
        $params["url"] = $token;
//        ThirdPartyLogs::setLog("BOXME","getcourier","weshopdev",$params,$response->getData());
        if(!$response->isOk){
//            $courierFailObject->message ='Request Failed';
            return null;
        }
        $response = $response->getData();
        return $response;
    }

    public static function CreateOrderShipmentBM($ship_from ,$ship_to,$shipment,$config){
        $params['ship_from'] = (array)$ship_from;
        if(!$ship_from->zipcode)
            unset($params['ship_from']['zipcode']);

        $params['ship_to'] = (array)$ship_to;
        unset($params['ship_to']['pickup_id']);
        if(!$ship_to->zipcode)
            unset($params['ship_to']['zipcode']);
        $params['shipments'] = (array)$shipment;
        unset($params['shipments']['cod_amount']);

        $params['config'] = (array)$config;

        $params['payment']['cod_amount']= $shipment->cod_amount;

        $token = Yii::$app->params['boxme'][$ship_to->country_id == 1 ? 'vn' : 'id']['TOKEN'];
        $url = Yii::$app->params['boxme'][$ship_to->country_id == 1 ? 'vn' : 'id']['URL'].'v1/courier/pricing/create_order';

        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->addHeaders([
            'Authorization' => "Token $token"
        ]);
        $request->setFormat('json');
        $request->setMethod('POST');
        $request->setData($params);
        $response = $client->send($request);
        $params['url'] = $url;
        $params['token'] = $token;
//        ThirdPartyLogs::setLog("BOX_ME","pricing/create_order","weshopdev",($params),$response->getData());
        if(!$response->isOk){
//            $courierFailObject->message ='Request Failed';
            $res = $response->getData();
            return $res['messages'];
        }
        $res = $response->getData();
        return $res;
    }

    public static function GetDetail($code,$page = 1,$contry = 'vn',$q = ""){

        $url = 'https://wms.boxme.asia/v1/packing/detail/'.$code.'/?page='.$page;
        if($q){
            $url .= '&q='.$q;
        }
        $token = "Q9v5AX0JsM5nLWUs3zDt8YQN3z9a55qP";
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->addHeaders([
            'Authorization' => $token
        ]);
        $request->setFormat('json');
        $request->setMethod('GET');
        $response = $client->send($request);
        $params['url'] = $url;
        $params['token'] = $token;
//        ThirdPartyLogs::setLog("BOX_ME","pricing/create_order","weshopdev",($params),$response->getData());
        if(!$response->isOk){
//            $courierFailObject->message ='Request Failed';
            $res = $response->getData();
            print_r($res);
            die;
            return $res['messages'];
        }
        $res = $response->getData();
        return $res;
    }

    /**
     * @param Order $order
     * @param $tracking
     * @return bool
     * @throws \Exception
     */
    public static function CreateLiveShipment($order){
        $service = new SellerClient(ArrayHelper::getValue(Yii::$app->params,'BOXME_GRPC_SERVICE_SELLER','10.130.111.53:50060'), [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $user = User::findOne($order->customer_id);
        $pickUpId = '';
        $user_id_df = '';
        if (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
            $current = self::getParamDefault($order->store_id);
            $wh = ArrayHelper::getValue($params, "warehouses.$current", false);
            $pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id');
            $user_id_df = ArrayHelper::getValue($wh, 'ref_user_id');
        }
        if($user && $user->pickup_id && self::checkIsPrime($user)){
            $pickUpId = $user->pickup_id;
        }else{
            $user = null;
        }
        $param = [];
        $param['shipping_method'] = 6;
        $param['pickup_id'] = $pickUpId;
        $param['ff_id'] = $pickUpId;
        $param['user_id'] = self::checkIsPrime($user) ? $user->bm_wallet_id : $user_id_df;
        $param['procducts'] = [];
        foreach ($order->products as $datum){
            $temp = [
              'bsin' => TextUtility::GenerateBSinBoxMe($datum->id),
              'quantity' => $datum->quantity_customer,
              'img_check' => 1,
              'description' => $datum->product_name
            ];
            $param['procducts'][] = $temp;
        }
        $arrTracking = $order->tracking_codes ? explode(',',$order->tracking_codes) : [];
        if(!$order->tracking_codes){
            ThirdPartyLogs::setLog('gprc','create_shipment', 'Create error: not have tracking code', [],[]);
            return 'Not have tracking code.';
        }
        if(!$order->order_boxme){
            ThirdPartyLogs::setLog('gprc','create_shipment', 'Create error: not have order boxme', [],[]);
            return 'Not have order boxme.';
        }
        $param['packages'] = [];
        foreach ($arrTracking as $key => $trackingCode){
            $temp = [
                'code' => $trackingCode,
                'weight' => ($order->total_weight_temporary * 1000)/$order->total_quantity,
                'quantity' => $order->total_quantity,
                'width' => 5,
                'length' => 5,
                'height' => 5,
                'description' => ''
            ];
            $desc = '';
            foreach ($order->products as $product){
                $desc .= " | ".$product->note_boxme;
            }
            $temp['description'] = $desc;
            $param['procducts'][] = $temp;
        }
        $param['tracking']['type'] = 2;
        $param['tracking']['tracking_number'] = $order->order_boxme;
        $param['list_order'] = [
            [
                'tracking_number' => $order->order_boxme,
                'parcel_number' => '',
                'country_code' => $order->store ? $order->store->country_code : 'VN',
            ]
        ];
        $data = [
            'Country' => $order->store ? $order->store->country_code : 'VN',
            'UserId' => self::checkIsPrime($user) ? $user->bm_wallet_id : $user_id_df,
            'Source' => 2,
            'Param' => json_encode($param),
        ];
        $request = new CreateShipmentRequest($data);

        $apires = $service->CreateShipment($request)->wait();
        list($rs,$stt) = $apires;
        /** @var CreateShipmentResponse $rs */
        if($rs && !$rs->getError()){
            $data_rs = json_decode($rs->getData(),true);
            $shipment_code = ArrayHelper::getValue($data_rs,'shipment_code');
            $order->shipment_boxme = $shipment_code ? ($order->shipment_boxme ? $order->shipment_boxme.','.$shipment_code : $shipment_code) : $order->shipment_boxme;
            $order->save(0);
            ThirdPartyLogs::setLog('gprc','create_shipment', 'Create success', $data,$rs->getData());
            return $order->shipment_boxme;
        }
        ThirdPartyLogs::setLog('gprc','create_shipment', 'Create error: '.$rs->getMessage(), $data,[$rs->getError(),$rs->getMessage(),$rs->getData(),$stt]);
        return $rs->getMessage();
    }

    /**
     * @param Product $product
     * @return bool | array | string
     * @throws \Exception
     */
    public static function SyncProduct($product){
        $service = new SellerClient(ArrayHelper::getValue(Yii::$app->params,'BOXME_GRPC_SERVICE_SELLER','206.189.94.203:50060'), [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $user_id_df = '';
        if (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
            $current = self::getParamDefault($product->order->store_id);
            $wh = ArrayHelper::getValue($params, "warehouses.$current", false);
            $user_id_df = ArrayHelper::getValue($wh, 'ref_user_id');
        }
        $user = User::findOne($product->order->customer_id);
        $data = [];
        $data['country'] = $product->order->store->country_code;
        $data['seller_id'] = self::checkIsPrime($user) ? $user->bm_wallet_id : $user_id_df;
        $data['category_id'] = $product->category_id;
        $data['seller_sku'] = TextUtility::GenerateBSinBoxMe($product->id);
        $data['bsin'] = TextUtility::GenerateBSinBoxMe($product->id);
        $data['name'] = $product->product_name;
        $data['name_local'] = $product->product_name;
        $data['type_sku'] = 1;
        $data['desc'] = $product->product_name;
        $data['link'] = $product->link_origin;
        $data['price'] = $product->total_price_amount_local;
        $data['price_sale'] = $product->price_amount_local;
        $data['active'] = 1;
        $data['weight'] = $product->total_weight_temporary ? $product->total_weight_temporary * 1000 : 500;
        $data['unit_weight'] = 'g';
        $data['volume'] = '';
        $data['tag'] = '';
        $data['warehouse_condition'] = 1;
        $data['supplier_name'] = '';
        $data['min_quantity'] = 1;
        $data['images'] = [
            $product->link_img
        ];
        $request = new SyncProductRequest(['Data' => json_encode($data)]);
        $apires = $service->syncProduct($request)->wait();
        /** @var SyncProductResponse $response */
        list($response, $status) = $apires;
        ThirdPartyLogs::setLog('gprc','sync_product', $response ? $response->getMessage() : "Sync fail", $data,[$response->getMessage(),$response->getError(),$status]);
        return $response ? $response->getMessage() : [ false , $status];

    }

    /**
     * @param Order $order
     * @return bool|string|array
     */
    public static function CreateOrder($order) {
        if ($order->order_boxme){
            return false;
        }
        $hostname = ArrayHelper::getValue(Yii::$app->params,'BOXME_GRPC_SERVICE_COURIER','10.130.111.53:50056');
        $service = new CourierClient($hostname, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);

        $user_id_df = '';
        $pickUpId = '';
        if (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
            $current = self::getParamDefault($order->store_id);
            $wh = ArrayHelper::getValue($params, "warehouses.$current", false);
            $pickUpId = ArrayHelper::getValue($wh, 'ref_pickup_id');
            $user_id_df = ArrayHelper::getValue($wh, 'ref_user_id');
        }
        $country = SystemCountry::findOne($order->receiver_country_id);
        $user = User::findOne($order->customer_id);
        if($user && $user->pickup_id && self::checkIsPrime($user)){
            $pickUpId = $user->pickup_id;
        }else{
            $user = null;
        }
        $shipTo = [
            'contact_name' => $order->receiver_name,
            'company_name' => '',
            'email' => $order->receiver_email,
            'address' => $order->receiver_address,
            'address2' => $order->buyer_address,
            'phone' => $order->receiver_phone,
            'phone2' => $order->buyer_phone,
            'province' => $order->receiver_province_id,
            'district' => $order->receiver_district_id,
            'country' => $country ? $country->country_code : 'VN',
            'zipcode' => $order->receiver_post_code,
        ];
        $item = [];
        foreach ($order->products as $product){
            $item[] = [
                'sku' => strtolower($product->portal) == 'ebay' ? $product->sku : $product->parent_sku,
                'label_code' => '',
                'origin_country' => 'US',
                'name' => $product->product_name,
                'desciption' => '',
                'weight' => WeshopHelper::roundNumber(($product->total_weight_temporary * 1000 / $product->quantity_customer)),
                'amount' => WeshopHelper::roundNumber($product->total_final_amount_local),
                'quantity' => $product->quantity_customer,
            ];
        }
        $data = [
            'ship_from' => [
                'country' => 'US',
                'pickup_id' => $pickUpId
            ],
            'ship_to' => $shipTo,
            'shipments' => [
                'content' => '',
                'total_parcel' => count($order->products),
                'total_amount' => $order->total_final_amount_local,
                'description' => '',
                'amz_shipment_id' => '',
                'chargeable_weight' => WeshopHelper::roundNumber($order->total_weight_temporary * 1000),
                'parcels' => [
                    [
                        'weight' => WeshopHelper::roundNumber($order->total_weight_temporary * 1000),
                        'amount' => $order->total_final_amount_local,
                        'description' => "Order Weshop ({$order->ordercode}) .Product {$order->portal}",
                        'items' => $item
                    ]
                ]
            ],
            'config' => [
                'preview' => 'Y',
                'return_mode' => 0,
                'insurance' => 'N',
                'document' => 0,
                'currency' => $order->store->currency,
                'unit_metric' => 'metric',
                'sort_mode' => 'best_rating',
                'auto_approve' => 'Y',
                'create_by' => 0,
                'order_type' => 'dropship',
                'check_stock' => 'N',
                'include_special_goods' => 'N'
            ],
            'payment' => [
                'cod_amount' => 0,
                'fee_paid_by' => 'sender'
            ],
            'referral' => [
                'order_number' => $order->ordercode,
            ]
        ];
        $data_rq = [
            'Data' => json_encode($data),
            'UserId' => self::checkIsPrime($user) ? $user->bm_wallet_id : $user_id_df,
            'CountryCode' => $country ? $country->country_code : 'VN',
        ];
        $request = new CreateOrderRequest($data_rq);
        /** @var CreateOrderResponse $rs */
        $apirs = $service->CreateOrder($request)->wait();
        list($rs, $stt) = $apirs;
        if($rs && !$rs->getError()){
            $order->order_boxme = $rs->getData() ? $rs->getData()->getTrackingNumber() : '';
            $order->save(0);
            ThirdPartyLogs::setLog('gprc','create_order_BM', $rs->getData() ? $rs->getData()->getTrackingNumber() : 'Fail', $data_rq,[$rs->getError(),$rs->getErrorCode(),$rs->getErrorMessage(),$rs->getData(),$stt]);
            return [true,$rs->getData() ? $rs->getData()->getTrackingNumber() : ''];
        }
        ThirdPartyLogs::setLog('gprc','create_order_BM', 'Fail', $data_rq,[$rs->getError(),$rs->getErrorCode(),$rs->getErrorMessage(),$rs->getData(),$stt]);
        return [false,$rs->getErrorMessage()];
    }
    public static function checkIsPrime($user) {
        return $user && $user->bm_wallet_id && $user->vip > 0 && $user->vip_end_time > time();
    }
    public static function getParamDefault($store_id) {
        if (YII_ENV != 'prod') {
            if($store_id == 7){
                return 'sandbox_id';
            }else{
                return 'sandbox_vn';
            }
        }else{
            if($store_id == 7){
                return 'ws_id';
            }else{
                return 'ws_vn';
            }
        }
    }
}