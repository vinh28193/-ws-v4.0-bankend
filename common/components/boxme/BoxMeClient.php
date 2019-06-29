<?php
namespace common\components\boxme;
use common\models\boxme\ConfigForm;
use common\models\boxme\ShipToForm;
use common\models\boxme\ShipmentForm;
use linslin\yii2\curl\Curl;
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
    public static function CreateLiveShipment($data,$tracking){
        $param = [];
        $param['shipping_method'] = 5;
        $param['pickup_id'] = 39412;
        $param['ff_id'] = 39412;
        $param['user_id'] = 41;
        $param['procducts'] = [];
        foreach ($data as $datum){
            $temp = [
              'bsin' => $datum['sku'],
              'quantity' => $datum['quantity'],
              'img_check' => $datum['img_check'],
              'description' => $datum['description']
            ];
            $param['procducts'][] = $temp;
        }
        $param['packages'] = [];
        /*foreach ($data as $datum){
            $temp = [
                'code' => $datum['packing_code'],
                'weight' => $datum['weight'],
                'width' => $datum['width'],
                'length' => $datum['length'],
                'quantity' => $datum['quantity'],
                'height' => $datum['height'],
                'description' => $datum['description'],
            ];
            $param['packages'][] = $temp;
        }*/
        $param['tracking']['type'] = 2;
        $param['tracking']['tracking_number'] = $tracking;
        $curl = new Curl();
        $response = $curl->setRawPostData(json_encode($param))
            ->setHeader('Content-Type','application/json')
            ->setHeader('Authorization','Token 424d31352012b39b1c399a669ab4a22a230d74d1ca2f0012e1079a8199c9fbd6')
            ->post('https://oms.boxme.asia/api/v1/sellers/shipments/create/');
//        Yii::debug($response);
        print_r(json_decode($response,true));
        die;
        return $response;
    }


}