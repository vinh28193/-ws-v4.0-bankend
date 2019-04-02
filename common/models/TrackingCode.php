<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:41
 */

namespace common\models;

use common\models\db\TrackingCode as DbTrackingCode;

class TrackingCode extends DbTrackingCode
{
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
        $model->setAttributes($this->toArray());
//        print_r($model);die;
        return $model->save($validate);
    }
}
