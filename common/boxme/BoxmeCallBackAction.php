<?php


namespace common\boxme;


use common\models\draft\DraftPackageItem;
use common\models\DeliveryNote;
use common\models\Shipment;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\base\Action;

class BoxmeCallBackAction extends Action
{

    public function run(){
        $post = Yii::$app->request->post();
        $response = null;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(isset($post['StatusCode']) && isset($post['TrackingCode']) && ($trackingCode = $post['TrackingCode'])){
            $this->updateOrderTracking($post);
        }elseif (isset($post['packing_code']) && isset($post['packing_code'])){
            $response = $this->updateItem($post);
            Yii::info($response);
        }
        $success = true;
        $message = 'update callback success, time:'.date('Y-m-d H:i:s');
        if(is_array($response) && count($response) === 2 && isset($response['success'])){
            $success = $response['success'];
            if($success === false && isset($response['info']) && is_string($response['info'])){
                $message = $response['info'];
            }
        }

        return ['success' => $success, 'message' => $message];
    }

    private function updateItem($post){
        $res =  WarehouseInspect::update($post);
        $res = array_combine(['success','info'],$res);
        return $res;
    }

    private function updateOrderTracking($post){
        if(($statusCode = ArrayHelper::getValue($post,'StatusCode')) !== null && (($trackingCode = ArrayHelper::getValue($post,'TrackingCode')) !== null && ($shipment = $this->findShipment($trackingCode)) !== null)){
           switch ($statusCode){
               case 200:  // 200: đơn đã duyêt
                   $shipment->shipment_status = Shipment::STATUS_APPROVED;
                   $shipment->save(false);
                   // update package
//                    foreach ($shipment->packages as $package){
//
//                    }
                    DeliveryNote::updateAll([
                        'current_status' => DeliveryNote::STATUS_REQUEST_SHIP_OUT
                    ],['shipment_id' => $shipment->id]);
                    break;
               case 300:  // 3000: Hãng vận chuyển đã lấy hàng
                   $shipment->shipment_status = Shipment::STATUS_PICKING;
                   $shipment->save(false);

                   DeliveryNote::updateAll([
                       'current_status' => DeliveryNote::STATUS_DELIVERING_TO_CUSTOMER,
                   ],['shipment_id' => $shipment->id]);

                   DraftPackageItem::updateAll([
                        'stock_out_local' => $this->today()
                   ],['shipment_id' => $shipment->id]);
                   break;
           }
        }
    }

    /**
     * @param $bmCode
     * @return Shipment|null
     */
    protected function findShipment($bmCode){
        return Shipment::findOne(['shipment_code' =>$bmCode]);
    }

    public function today(){
        return Yii::$app->getFormatter()->asTimestamp('now');
    }
}