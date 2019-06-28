<?php
namespace common\components\boxme;
use common\models\boxme\ConfigForm;
use common\models\boxme\ShipToForm;
use common\models\boxme\ShipmentForm;
use Yii;

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
    public static function CreateLiveShipment(){
        $param = [];
        $param['shipping_method'] = 5;
        $param['pickup_id'] = 5;
        $param['ff_id'] = 5;
        $param['user_id'] = 5;
        $param['procducts'] = 5;
    }


}