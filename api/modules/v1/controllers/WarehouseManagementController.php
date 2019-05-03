<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\TrackingCode;
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
        if($tab){
            $data->andWhere(['tracking_code.status' => strtoupper($tab)]);
        }
        $res['total'] = $data->count();
        $res['data'] = $data = $data->orderBy('id desc')->limit(20)->offset(0)->asArray()->all();
        return $this->response(true,'ok', $res);
    }
    public function actionCreate(){
        if(!$this->post['tracking_codes'] || !$this->post['time_received']){
            return $this->response(false, 'Tracking code and time received cannot null!');
        }
        $trackingS = explode(',',str_replace(' ','',$this->post['tracking_codes']));
        $time = str_replace('T',' ',strtoupper($this->post['time_received']));
        $time = str_replace('Z','',strtoupper($time));
        $time = explode('.',$time)[0];
        foreach ($trackingS as $tracking){
            $trackingNew = new TrackingCode();
            $trackingNew->tracking_code = $tracking;
            $trackingNew->status = TrackingCode::STATUS_US_RECEIVED;
            $trackingNew->CreateOrUpdate(false);
        }
        return $this->response(true, 'Mark us received success!');
    }
}