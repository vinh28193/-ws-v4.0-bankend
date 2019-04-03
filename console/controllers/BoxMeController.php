<?php


namespace console\controllers;


use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftWastingTracking;
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
            $pageTotal = 2;
            for($page = 1; $page <= $pageTotal;$page++){
                $data = \BoxMeClient::GetDetail($code,1,$country);
                if($data['success']){
                    if ($page == 1) $pageTotal = $data['total_page'];

                    echo "Tổng tracking ".$data['total_item'].".".PHP_EOL;
                    echo "Tổng trang ".$data['total_page'].".".PHP_EOL;
                    echo "trang ".$page.".".PHP_EOL;
                    foreach ($data['results'] as $result){
                        $soi = ArrayHelper::getValue($result,'soi_tracking');
                        $tracking = ArrayHelper::getValue($result,'tracking_code');
                        $tag_code = ArrayHelper::getValue($result,'tag_code');
                        $volume = ArrayHelper::getValue($result,'volume');
                        $quantity = ArrayHelper::getValue($result,'quantity');
                        $weight = ArrayHelper::getValue($result,'weight');
                        $status = ArrayHelper::getValue($result,'status');
                        $item_name = ArrayHelper::getValue($result,'item');
                        $note = ArrayHelper::getValue($result,'note');
                        $images = ArrayHelper::getValue($result,'images');
                        $image = "";
                        $vol = $volume ? explode('x',strtolower($volume)) : null;
                        foreach ($images as $k => $img){
                            $image .= $k == 0 ? ArrayHelper::getValue($img,'urls') : ','.ArrayHelper::getValue($img,'urls');
                        }
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
                                $wasting->quantity = $quantity;
                                $wasting->weight = $weight;
                                $wasting->status = $status;

                                $wasting->dimension_l = isset($vol[0]) ? $vol[0] : null;
                                $wasting->dimension_w = isset($vol[1]) ? $vol[1] : null;
                                $wasting->dimension_h = isset($vol[2]) ? $vol[2] : null;
                                $wasting->item_name = $item_name;
                                $wasting->image = $image;
                                $wasting->warehouse_tag_boxme = $tag_code;
                                $wasting->note_boxme = $note;
                                $wasting->createOrUpdate();
                            }else{
                                $find->number_get_detail = $find->number_get_detail?$find->number_get_detail+1:1;
                                $find->save(0);
                                $draft = new DraftBoxmeTracking();
                                $draft->tracking_code = $tracking;
                                $draft->manifest_code = $detail->manifest_code;
                                $draft->manifest_id = $detail->manifest_id;
                                $draft->quantity = $quantity;
                                $draft->weight = $weight;
                                $draft->status = $status;

                                $draft->dimension_l = isset($vol[0]) ? $vol[0] : null;
                                $draft->dimension_w = isset($vol[1]) ? $vol[1] : null;
                                $draft->dimension_h = isset($vol[2]) ? $vol[2] : null;
                                $draft->item_name = $item_name;
                                $draft->image = $image;
                                $draft->warehouse_tag_boxme = $tag_code;
                                $draft->note_boxme = $note;
                                $draft->createOrUpdate();
                            }
                        }else{
                            /** @var DraftDataTracking[] $finds */
                            $finds =DraftDataTracking::find()->where([
                                'tracking_code' => $tracking,
                            ])->all();
                            if($finds){
                                foreach ($finds as $find){
                                    $find->number_get_detail = $find->number_get_detail?$find->number_get_detail+1:1;
                                    $find->save(0);
                                    $draft = new DraftBoxmeTracking();
                                    $draft->tracking_code = $tracking;
                                    $draft->manifest_code = $detail->manifest_code;
                                    $draft->manifest_id = $detail->manifest_id;
                                    $draft->quantity = $quantity;
                                    $draft->weight = $weight;
                                    $draft->status = $status;

                                    $draft->dimension_l = isset($vol[0]) ? $vol[0] : null;
                                    $draft->dimension_w = isset($vol[1]) ? $vol[1] : null;
                                    $draft->dimension_h = isset($vol[2]) ? $vol[2] : null;
                                    $draft->item_name = $item_name;
                                    $draft->image = $image;
                                    $draft->warehouse_tag_boxme = $tag_code;
                                    $draft->note_boxme = $note;
                                    $draft->createOrUpdate();
                                }
                            }else{
                                $wasting = new DraftWastingTracking();
                                $wasting->tracking_code = $tracking;
                                $wasting->manifest_code = $detail->manifest_code;
                                $wasting->manifest_id = $detail->manifest_id;
                                $wasting->quantity = $quantity;
                                $wasting->weight = $weight;
                                $wasting->status = $status;

                                $wasting->dimension_l = isset($vol[0]) ? $vol[0] : null;
                                $wasting->dimension_w = isset($vol[1]) ? $vol[1] : null;
                                $wasting->dimension_h = isset($vol[2]) ? $vol[2] : null;
                                $wasting->item_name = $item_name;
                                $wasting->image = $image;
                                $wasting->warehouse_tag_boxme = $tag_code;
                                $wasting->note_boxme = $note;
                                $wasting->createOrUpdate();
                            }
                        }
                    }
                }else{
                    echo "Lỗi gọi api".PHP_EOL;
                    echo $data['message'].PHP_EOL;
                    break;
                }
            }
        }else{
            echo "Đã hết yêu cầu lấy chi tiết tracking trong lô.".PHP_EOL;
            die;
        }
    }
}