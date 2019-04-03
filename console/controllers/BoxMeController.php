<?php


namespace console\controllers;


use common\models\db\DraftBoxmeTracking;
use common\models\db\DraftWastingTracking;
use common\models\draft\DraftDataTracking;
use common\modelsMongo\RequestGetDetailBoxMe;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class BoxMeController extends Controller
{
    public function actionCheckDetailBoxme(){
        /** @var RequestGetDetailBoxMe $detail */
        $detail = RequestGetDetailBoxMe::find()->where(['status' => RequestGetDetailBoxMe::STATUS_NEW])->one();
        if($detail){
            echo "Bắt đầu lấy chi tiết tracking trong lô $detail->manifest_code.".PHP_EOL;
            $code = $detail->manifest_code.'-'.$detail->manifest_id;
            $country = $detail->store_id == 1 ? 'vn' : 'id';
            $data = \BoxMeClient::GetDetail($code,1,$country);
            if($data['success']){
                echo "Tổng tracking ".$data['total_item'].".".PHP_EOL;
                echo "Tổng trang ".$data['total_page'].".".PHP_EOL;
                $pageTotal = $data['total_page'];
                foreach ($data['results'] as $result){
                    $soi = ArrayHelper::getValue($result,'soi_tracking');
                    $tracking = ArrayHelper::getValue($result,'tracking_code');
                    $tag_code = ArrayHelper::getValue($result,'tag_code');
                    $volume = ArrayHelper::getValue($result,'volume');
                    $quantity = ArrayHelper::getValue($result,'quantity');
                    $weight = ArrayHelper::getValue($result,'weight');
                    $status = ArrayHelper::getValue($result,'status');
                    $note = ArrayHelper::getValue($result,'note');
                    if($soi){
                        /** @var DraftDataTracking $find */
                        $find =DraftDataTracking::find()->where([
                            'tracking_code' => $tracking,
                            'product_id' => $soi,
                        ])->one();
                        if(!$find){
                            $wasting = new DraftWastingTracking();
                            $wasting->tracking_code = $tracking;
                            $wasting->manifest_code = $detail->manifest_code;
                            $wasting->manifest_id = $detail->manifest_id;
                            $wasting->quatity = $quantity;
                            $wasting->weight = $weight;
                            $wasting->status = $status;
                            $vol = $volume ? explode('x',strtolower($volume)) : null;
                            $wasting->dimension_l = isset($vol[0]) ? $vol[0] : null;
                            $wasting->dimension_w = isset($vol[1]) ? $vol[1] : null;
                            $wasting->dimension_h = isset($vol[2]) ? $vol[2] : null;
                        }else{

                        }
                    }else{
                        $finds =DraftDataTracking::find()->where([
                            'tracking_code' => $tracking,
                        ])->all();
                    }
                }
            }else{
                echo "Lỗi gọi api".PHP_EOL;
                echo $data['message'].PHP_EOL;
            }
        }else{
            echo "Đã hết yêu cầu lấy chi tiết tracking trong lô.".PHP_EOL;
            die;
        }
    }
}