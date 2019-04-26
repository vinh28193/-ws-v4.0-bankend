<?php


namespace common\boxme;


use Yii;
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

    }
}