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
    public function CreateOrUpdate(){
        if($this->tracking_code){
            $model = self::find()->where(['tracking_code' => $this->tracking_code,'remove' => 0])->one();
            if ($model){
                $this->order_ids = $model->order_ids.','.$this->order_ids;
            }else{
                $model = new self();
            }
        }else{
            $model = new self();
        }
        $model->setAttributes($model->toArray());
        return $model->save();
    }
}
