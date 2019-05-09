<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\TrackingCode;
use common\models\Warehouse;
use yii\helpers\ArrayHelper;

class WarehouseManagementController extends BaseApiController
{
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true),
            ]
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['get'],
            'create' => ['post'],
        ];
    }

    public function actionIndex() {
        $limit = \Yii::$app->request->get('limit',20);
        $page = \Yii::$app->request->get('page',1);
        $data = $this->getFilter();
        $res['total'] = $data->count();
        if($limit != 'all'){
            $data = $data->limit($limit)->offset($page*$limit - $limit);
        }
        $res['data'] = $data = $data->orderBy('id desc')->asArray()->all();
        return $this->response(true,'ok', $res);
    }
    public function actionCreate(){
        if(!$this->post['tracking_codes'] || !$this->post['time_received'] || !$this->post['warehouse_received']){
            return $this->response(false, 'Tracking code , time received and warehouse received cannot null!');
        }
        $warehouse = Warehouse::findOne($this->post['warehouse_received']);
        if(!$warehouse){
            return $this->response(false, 'Cannot find warehouse '.$this->post['warehouse_received'].'!');
        }
        $trackingS = explode(',',str_replace(' ','',$this->post['tracking_codes']));

        foreach ($trackingS as $tracking){
            if($tracking){
                $tracking = trim($tracking,"\t\n");
                $trackingNew = new TrackingCode();
                $trackingNew->tracking_code = $tracking;
                $trackingNew->stock_in_us = strtotime($this->post['time_received']);
                $trackingNew->warehouse_us_id = $warehouse->id;
                $trackingNew->warehouse_us_name = $warehouse->name;
                $trackingNew->store_id = ArrayHelper::getValue($this->post,'store_1',1);
                $trackingNew->status = TrackingCode::STATUS_US_RECEIVED;
                $trackingNew->CreateOrUpdate(false);
            }
        }
        return $this->response(true, 'Mark us received success!');
    }
    public function getFilter(){
        $manifestCode = \Yii::$app->request->get('manifestCode');
        $trackingCode = \Yii::$app->request->get('trackingCode');
        $packageCode = \Yii::$app->request->get('packageCode');
        $shipmentCode = \Yii::$app->request->get('shipmentCode');
        $tracking_ws = \Yii::$app->request->get('WsTrackingCode');
        $tab = \Yii::$app->request->get('tabFilter');
        $data = TrackingCode::find()->with(['draftDataTrackings'])->where(['tracking_code.remove' => 0]);
        if($manifestCode){
            $data->andWhere(['like','tracking_code.manifest_code' , $manifestCode]);
        }
        if($trackingCode){
            $data->andWhere(['like','tracking_code.tracking_code' , $trackingCode]);
        }
        if($tracking_ws || $packageCode || $shipmentCode){
            $data->innerJoin('draft_data_tracking','draft_data_tracking.tracking_code = tracking_code.tracking_code');
            if($tracking_ws){
                $data->andWhere(['like','draft_data_tracking.ws_tracking_code' , $tracking_ws]);
            }
            if($packageCode || $shipmentCode){
                $data->innerJoin('package','package.ws_tracking_code = draft_data_tracking.ws_tracking_code');
                if($packageCode){
                    $data->andWhere(['like','package.package_code' , $packageCode]);
                }
                if($shipmentCode){
                    $data->innerJoin('shipment','shipment.id = package.shipment_id');
                    $data->andWhere(['like','shipment.shipment_code' , $shipmentCode]);
                }
            }
        }
        if($tab && $tab != 'all'){
            $data->andWhere(['tracking_code.status' => strtoupper($tab)]);
        }
        return $data;
    }
}