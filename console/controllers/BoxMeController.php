<?php


namespace console\controllers;


use common\components\boxme\BoxMeClient;
use common\components\TrackingCode;
use common\models\db\DraftExtensionTrackingMap;
use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftPackageItem;
use common\models\draft\DraftWastingTracking;
use common\models\draft\DraftDataTracking;
use common\models\Order;
use common\models\Product;
use common\models\ProductFee;
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
                $detail->send_request_latest = 'https://wms.boxme.asia/v1/packing/detail/' . $code . '/?page=' . $page;
                $detail->count_request = $detail->count_request ? $detail->count_request + 1 : 1;
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
                        TrackingCode::UpdateTracking($result);
                    }
                } else {
                    echo "Lỗi gọi api" . PHP_EOL;
                    $detail->status = RequestGetDetailBoxMe::STATUS_GET_FAIL;
                    echo $data['message'] . PHP_EOL;
                    break;
                }
            }
            /** @var DraftDataTracking[] $draftData */
            $draftData = DraftDataTracking::find()->where([
                'manifest_id' => $detail->manifest_id,
                'manifest_code' => $detail->manifest_code,
            ])->andWhere(['<>', 'status', DraftDataTracking::STATUS_CHECK_DETAIL])->all();
            foreach ($draftData as $datum) {
                $missing = new DraftMissingTracking();
                $missing->tracking_code = $datum->tracking_code;
                $missing->manifest_code = $datum->manifest_code;
                $missing->manifest_id = $datum->manifest_id;
                $missing->product_id = $datum->product_id;
                $missing->order_id = $datum->order_id;
                $missing->purchase_invoice_number = $datum->purchase_invoice_number;
                $missing->quantity = $datum->quantity;
                $missing->dimension_h = $datum->dimension_h;
                $missing->dimension_l = $datum->dimension_l;
                $missing->dimension_w = $datum->dimension_w;
                $missing->weight = $datum->weight;
                $missing->status = $datum->status;
                $missing->created_at = time();
                $missing->updated_at = time();
                $missing->createOrUpdate(false);
            }
            $detail->status = RequestGetDetailBoxMe::STATUS_GET_DONE;
            $detail->updated_at = date('Y-m-d H:i:s');
            $detail->save();
        } else {
            echo "Đã hết yêu cầu lấy chi tiết tracking trong lô." . PHP_EOL;
            die('STOP');
        }
        echo "Kết thúc quá trình." . PHP_EOL;
        die('STOP');
    }

    public function actionMergeExtensionUsSending()
    {
        $this->stdout("Bắt đầu merge ...." . PHP_EOL);
        /** @var \common\models\TrackingCode[] $trackingCode */
        $trackingCode = \common\models\TrackingCode::find()->where(['status_merge' => null])
            ->orWhere(['status_merge' => \common\models\TrackingCode::STATUS_MERGE_NEW])
            ->limit(500)->all();
        $this->stdout("Có tổng cộng: " . count($trackingCode) . " tracking sẽ được chạy." . PHP_EOL);
        foreach ($trackingCode as $tracking) {
            if (!$tracking->tracking_code) {
                $this->stdout("Không có tracking code...." . PHP_EOL);
                continue;
            }
            $this->stdout($tracking->tracking_code . PHP_EOL);
            /** @var DraftDataTracking[] $draft_data */
            $draft_data = DraftDataTracking::find()->where([
                'tracking_code' => $tracking->tracking_code,
            ])->all();
            if ($draft_data) {
                foreach ($draft_data as $datum) {
                    $datum->manifest_id = $tracking->manifest_id;
                    $datum->manifest_code = $tracking->manifest_code;
                    $datum->updated_at = time();
                    $datum->status = in_array($datum->status, [DraftDataTracking::STATUS_CHECK_DETAIL, DraftDataTracking::STATUS_CHECK_DONE]) ? $datum->status : ($datum->status == DraftDataTracking::STATUS_EXTENSION ? DraftDataTracking::STATUS_CHECK_DONE : DraftDataTracking::STATUS_MAKE_US_SENDING);
                    $datum->save(false);
                }
            } else {
                $draft_data_one = new DraftDataTracking();
                $draft_data_one->tracking_code = $tracking->tracking_code;
                $draft_data_one->manifest_id = $tracking->manifest_id;
                $draft_data_one->manifest_code = $tracking->manifest_code;
                $draft_data_one->created_at = time();
                $draft_data_one->updated_at = time();
                $draft_data_one->status = DraftDataTracking::STATUS_MAKE_US_SENDING;
                $draft_data_one->save(0);
            }
            $tracking->status_merge = \common\models\TrackingCode::STATUS_MERGE_DONE;
            $tracking->save(0);
        }
        $this->stdout("Merge kết thúc ...." . PHP_EOL);
    }


    public function actionEmulatorExtension($manifest_code,$token)
    {
        echo "Bắt đầu giả lập dữ liệu extension: " . PHP_EOL;
        /** @var \common\models\TrackingCode[] $track */
        $track = \common\models\TrackingCode::find()
            ->where(['manifest_code' => $manifest_code])->limit(500)->all();
        $cn = round(count($track));
        echo "Sẽ có :" . $cn . " tracking được giả lập extension" . PHP_EOL;
        echo "[";
        $purchase_id = 2000;
        foreach ($track as $k => $item) {
            if ($k > $cn) {
                break;
            }
            $data = BoxMeClient::GetDetail($item->manifest_code . "-" . $item->manifest_id, 1, 'vn', $item->tracking_code);
            if ($data['success']) {
                foreach ($data['results'] as $key => $result) {
                    $soi = ArrayHelper::getValue($result, 'soi_tracking');
                    $tracking = ArrayHelper::getValue($result, 'tracking_code');
                    $quantity = ArrayHelper::getValue($result, 'quantity');
                    $soi = intval(str_replace('SOI-', '', $soi));
                    $soi = $soi ? $soi : null;
                    if ($soi) {
                        $order = $this->genOrder($soi,$token);
                        if($order){
                            $ext = DraftExtensionTrackingMap::find()->where([
                                'tracking_code' => $item->tracking_code,
                                'product_id' => $soi,
                                'order_id' => $order->id, //Todo Giả lập dữ liệu order tại đây
                                'purchase_invoice_number' => $purchase_id //Todo Giả lập dữ liệu order tại đây
                            ])->one();
                            if (!$ext) {
                                $ext = new DraftExtensionTrackingMap();
                                $ext->tracking_code = $item->tracking_code;
                                $ext->product_id = $soi;
                                $ext->order_id = $order->id;
                                $ext->purchase_invoice_number = "" . $purchase_id;
                                $ext->created_at = time();
                                $ext->updated_at = time();
                                $ext->created_by = 1;
                                $ext->updated_by = 1;
                            }
                            $ext->status = "US_RECEIVED";
                            $ext->quantity = $quantity;
                            $ext->number_run = $ext->number_run ? $ext->number_run + 1 : 1;
                            if (!$ext->save()) {
                                print_r($ext->errors);
                                die;
                            }
                            $draft_data = DraftDataTracking::find()->where([
                                'tracking_code' => $tracking,
                                'product_id' => $soi
                            ])->one();
                            if (!$draft_data) {
                                $draft_data = DraftDataTracking::find()->where([
                                    'tracking_code' => $tracking,
                                    'product_id' => null,
                                ])->one();
                                if (!$draft_data) {
                                    $tmp = DraftDataTracking::find()->where([
                                        'tracking_code' => $tracking,
                                    ])->one();
                                    $draft_data = new DraftDataTracking();
                                    $draft_data->created_at = time();
                                    $draft_data->updated_at = time();
                                    $draft_data->created_by = 1;
                                    $draft_data->updated_by = 1;
                                    if ($tmp) {
                                        $draft_data->manifest_code = $tmp->manifest_code;
                                        $draft_data->manifest_id = $tmp->manifest_id;
                                    }
                                }
                            }
                            $draft_data->tracking_code = $tracking;
                            $draft_data->product_id = $soi;
                            $draft_data->purchase_invoice_number = $order->id;
                            $draft_data->order_id = $purchase_id;
                            $draft_data->quantity = $quantity;
                            $draft_data->createOrUpdate(false);
                            echo "♪";
                        }
                    }
                }
            }
        }
        echo "]" . PHP_EOL;
        print_r("Kết thúc giả lập");
        die;
    }

    public function actionEmulatorCallback($manifest_code)
    {
        echo "Bắt đầu quá trình giả lập :" . PHP_EOL;
        /** @var DraftBoxmeTracking[] $track */
        $track = DraftBoxmeTracking::find()
            ->where(['manifest_code' => $manifest_code])->limit(500)->all();
        echo "Sẽ có :" . count($track) . " tracking được giả lập callback" . PHP_EOL;
        echo "[";
        foreach ($track as $item) {
            echo "♪";
            $data['packing_code'] = $item->manifest_code . '-' . $item->manifest_id;
            $data['soi_tracking'] = $item->product_id ? 'SOI-' . $item->product_id : 'NONE';
            $data['tracking_code'] = $item->tracking_code;
            $data['tag_code'] = $item->warehouse_tag_boxme;
            $data['tracking_type'] = "Normal";
            $data['volume'] = $item->dimension_l ? $item->dimension_l . "x" . $item->dimension_h . "x" . $item->dimension_w : "";
            $data['quantity'] = $item->quantity;
            $data['item'] = $item->item_name;
            $data['weight'] = $item->weight;
            $data['status'] = 'Close';
            $data['note'] = $item->note_boxme;
            $data['images'] = [];
            $images = explode(',', $item->image);
            foreach ($images as $img) {
                $tmp = [];
                $tmp['order_id'] = $item->warehouse_tag_boxme;
                $tmp['urls'] = $img;
                $data['images'][] = $tmp;
            }
            $url = 'http://weshop-v4.back-end.local.vn/test/callback-boxme';
            $client = new \yii\httpclient\Client();
            $request = $client->createRequest();
            $request->setFullUrl($url);
            $request->setFormat('json');
            $request->setMethod('POST');
            $request->setData($data);
            $response = $client->send($request);
            if (!$response->isOk) {
                $res = $response->getData();
//                return $res['messages'];
            }
            $res = $response->getData();
//            print_r($res);
        }
        echo "]" . PHP_EOL;
        print_r("Kết thúc giả lập");
        die;
    }

//    public function actionEmulatorOrder($token)
//    {
//        /** @var DraftPackageItem[] $draft_package */
//        $draft_package = DraftPackageItem::find()->where(['is not', 'product_id', null])->all();
//        foreach ($draft_package as $packageItem) {
//            $order = $this->genOrder($packageItem->product_id,$token);
//            if ($order) {
//                $packageItem->order_id = $order->id;
//                $packageItem->save();
//                Product::updateAll(['id' => $packageItem->product_id], ['order_id' => $order->id]);
//                ProductFee::updateAll(['product_id' => $packageItem->product_id], ['order_id' => $order->id]);
//            }
//        }
//    }

    /**
     * @param $product_id
     * @param $token
     * @return bool|Order|null
     */
    public function genOrder($product_id,$token){
        $product = Product::findOne($product_id);
        if($product){
            return Order::findOne($product->order_id);
        }
        $url = "http://weshop-v4.back-end.local.vn/v1/data";
        $data['sku'] = "312226695751";
        $data['seller'] = "yibe_98";
        $data['quantity'] = "1";
        $data['source'] = "ebay";
        $data['image'] = 'https://i.ebayimg.com/00/s/MTYwMFgxNjAw/z/iEcAAOSwLMFciIYI/$_12.JPG?set_id=880000500F';
        $data['parentSku'] = "312226695751";
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->addHeaders([
            'X-Access-Token' => $token
        ]);
        $request->setFormat('json');
        $request->setMethod('POST');
        $request->setData($data);
        $response = $client->send($request);
        if ($response->isOk) {
            $dta = json_decode($response->getContent(), true);
            if ($dta['status']) {
                $order_id = $dta['data']['order']['id'];
                $order = Order::findOne($order_id);
                if($order){
                    Product::updateAll(['id' => $product_id],['order_id' => $order->id]);
                    ProductFee::updateAll(['product_id' => $product_id],['order_id' => $order->id]);
                    return $order;
                }
            }
        }
        return false;
    }
}