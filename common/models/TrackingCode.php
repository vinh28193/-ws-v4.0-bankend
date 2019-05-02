<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:41
 */

namespace common\models;

use common\models\db\TrackingCode as DbTrackingCode;
use common\models\draft\DraftDataTracking;

/**
 * Class TrackingCode
 * @package common\models
 * @package DraftDataTracking[] $draftDataTrackings
 */
class TrackingCode extends DbTrackingCode
{
    const STATUS_US_RECEIVED = 'US_RECEIVED';
    const STATUS_US_SENDING = 'US_SENDING';
    const STATUS_LOCAL_RECEIVED = 'LOCAL_RECEIVED';
    const STATUS_LOCAL_INSPECTED = 'LOCAL_INSPECTED';
    const STATUS_LOCAL_INSPECTING = 'LOCAL_INSPECTED';
    const STATUS_CREATED_SHIPMENT = 'CREATED_SHIPMENT';
    const STATUS_CREATING_SHIPMENT = 'CREATING_SHIPMENT';
    const STATUS_DELIVERING = 'DELIVERING';
    const STATUS_DELIVERING_PART = 'DELIVERING_PART';
    const STATUS_AT_CUSTOMER = 'AT_CUSTOMER';
    const STATUS_AT_CUSTOMER_PART = 'AT_CUSTOMER_PART';
    const STATUS_MERGE_NEW = 'NEW';
    const STATUS_MERGE_DONE = 'DONE';
    public function CreateOrUpdate($validate = true){
        if($this->tracking_code){
            $model = TrackingCode::find()->where(['tracking_code' => $this->tracking_code,'remove' => 0])->one();
            if ($model){
                $this->order_ids = $model->order_ids.','.$this->order_ids;
                $this->quantity += $model->quantity;
                $this->created_by = $model->created_by;
                $this->created_at += $model->created_at;
            }else{
                $model = new self();
            }
        }else{
            $model = new TrackingCode();
        }
        $model->setAttributes($this->getAttributes());
//        print_r($model);die;
        return $model->save($validate);
    }
    public function getDraftDataTrackings(){
        return $this->hasMany(DraftDataTracking::className(),['tracking_code' => 'tracking_code', 'manifest_id' => 'manifest_id']);
    }
    public static function UpdateStatusTracking($trackingcode){
        /** @var DraftDataTracking[] $modals */
        $modals = DraftDataTracking::find()
            ->where(['tracking_code' => $trackingcode])->all();
//        $status = se
        foreach ($modals as $modal){

        }
    }
}
