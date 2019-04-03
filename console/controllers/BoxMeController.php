<?php


namespace console\controllers;


use common\components\boxme\BoxMeClient;
use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftWastingTracking;
use common\models\draft\DraftDataTracking;
use common\modelsMongo\RequestGetDetailBoxMe;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class BoxMeController extends Controller
{
    public function actionCheckDetailBoxme()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        /** @var RequestGetDetailBoxMe $detail */
        $detail = RequestGetDetailBoxMe::find()->where(['status' => RequestGetDetailBoxMe::STATUS_NEW])->one();
        if ($detail) {
            $detail->status = RequestGetDetailBoxMe::STATUS_PROCESSING;
            $detail->updated_at = date('Y-m-d H:i:s');
            $detail->save();
            echo "Bắt đầu lấy chi tiết tracking trong lô $detail->manifest_code." . PHP_EOL;
            $code = $detail->manifest_code . '-' . $detail->manifest_id;
            $country = $detail->store_id == 1 ? 'vn' : 'id';
            $pageTotal = 2;
            for ($page = 1; $page <= $pageTotal; $page++) {
                $data = BoxMeClient::GetDetail($code, $page, $country);
                $detail->send_request_latest = 'https://wms.boxme.asia/v1/packing/detail/'.$code.'/?page='.$page;
                $detail->get_response_latest = $data;
                $detail->time_run_latest = date('Y-m-d H:i:s');
                $detail->updated_at = date('Y-m-d H:i:s');
                $detail->save();
                if ($data['success']) {
                    if ($page == 1) $pageTotal = $data['total_page'];

                    echo "Tổng tracking " . $data['total_item'] . "." . PHP_EOL;
                    echo "Tổng trang " . $data['total_page'] . "." . PHP_EOL;
                    echo "trang " . $page . "." . PHP_EOL;
                    foreach ($data['results'] as $result) {
                        $soi = ArrayHelper::getValue($result, 'soi_tracking');
                        $tracking = ArrayHelper::getValue($result, 'tracking_code');
                        $tag_code = ArrayHelper::getValue($result, 'tag_code');
                        $volume = ArrayHelper::getValue($result, 'volume');
                        $quantity = ArrayHelper::getValue($result, 'quantity');
                        $weight = ArrayHelper::getValue($result, 'weight');
                        $status = ArrayHelper::getValue($result, 'status');
                        $item_name = ArrayHelper::getValue($result, 'item');
                        $note = ArrayHelper::getValue($result, 'note');
                        $images = ArrayHelper::getValue($result, 'images');
                        $image = "";
                        $vol = $volume ? explode('x', strtolower($volume)) : null;
                        echo "Tracking: " . $tracking . PHP_EOL;
                        echo "SOI: " . $soi . PHP_EOL;
                        foreach ($images as $k => $img) {
                            $image .= $k == 0 ? ArrayHelper::getValue($img, 'urls') : ',' . ArrayHelper::getValue($img, 'urls');
                        }
                        $soi = intval(str_replace('SOI-', '', $soi));
                        $soi = $soi ? $soi : null;
                        /** @var DraftDataTracking[] $finds */
                        $finds = DraftDataTracking::find()->where([
                            'tracking_code' => $tracking,
                            'product_id' => $soi,
                        ])->all();
                        if ($finds) {
                            echo "Có trong DraftDataTracking" . PHP_EOL;
                            foreach ($finds as $find) {
                                $find->number_get_detail = $find->number_get_detail ? $find->number_get_detail + 1 : 1;
                                $find->save(0);
                                $draft = new DraftBoxmeTracking();
                                $draft->tracking_code = $tracking;
                                $draft->manifest_code = $detail->manifest_code;
                                $draft->manifest_id = $detail->manifest_id;
                                $draft->quantity = $quantity;
                                $draft->weight = $weight;
                                $draft->product_id = $soi;
                                $draft->status = $status;

                                $draft->dimension_l = isset($vol[0]) ? $vol[0] : null;
                                $draft->dimension_w = isset($vol[1]) ? $vol[1] : null;
                                $draft->dimension_h = isset($vol[2]) ? $vol[2] : null;
                                $draft->item_name = $item_name;
                                $draft->image = $image;
                                $draft->warehouse_tag_boxme = $tag_code;
                                $draft->note_boxme = $note;
                                $draft->createOrUpdate(false);
                                DraftWastingTracking::updateAll([
                                    'status' => 'MERGED'
                                ],[
                                  'tracking_code' => $tracking,
                                  'product_id' => $soi,
                                ]);
                            }
                        } else {
                            echo "Không có trong DraftDataTracking" . PHP_EOL;
                            $wasting = new DraftWastingTracking();
                            $wasting->tracking_code = $tracking;
                            $wasting->manifest_code = $detail->manifest_code;
                            $wasting->manifest_id = $detail->manifest_id;
                            $wasting->product_id = $soi;
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
                            $wasting->createOrUpdate(false);
                        }
                    }
                } else {
                    echo "Lỗi gọi api" . PHP_EOL;
                    $detail->status = RequestGetDetailBoxMe::STATUS_GET_FAIL;
                    echo $data['message'] . PHP_EOL;
                    break;
                }
            }
            $detail->status = RequestGetDetailBoxMe::STATUS_GET_DONE;
            $detail->updated_at = date('Y-m-d H:i:s');
            $detail->save();
        } else {
            echo "Đã hết yêu cầu lấy chi tiết tracking trong lô." . PHP_EOL;
            die;
        }
    }
}