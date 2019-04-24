<?php


namespace console\controllers;


use common\components\boxme\BoxMeClient;
use common\components\lib\TextUtility;
use common\components\TrackingCode;
use \common\models\draft\DraftExtensionTrackingMap;
use common\models\db\PurchaseOrder;
use common\models\db\PurchaseProduct;
use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftPackageItem;
use common\models\draft\DraftWastingTracking;
use common\models\draft\DraftDataTracking;
use common\models\Manifest;
use common\models\Order;
use common\models\Package;
use common\models\PackageItem;
use common\models\Product;
use common\models\ProductFee;
use common\models\Warehouse;
use common\modelsMongo\RequestGetDetailBoxMe;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class BoxMeController extends Controller
{
    /**
     *
     */
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
        /** @var Manifest $manifest */
        $manifest = Manifest::find()->where(['status' => Manifest::STATUS_NEW])->one();
        if(!$manifest){
            echo "Stop. Đã hết lô.";
            die;
        }
        $manifest->status = Manifest::STATUS_MERGING;
        $manifest->save(0);
        /** @var \common\models\TrackingCode[] $trackingCode */
        $trackingCode = \common\models\TrackingCode::find()->where(['or',['status_merge' => null], ['status_merge' => \common\models\TrackingCode::STATUS_MERGE_NEW]])
            ->andWhere(['manifest_id' => $manifest->id])->all();
        $this->stdout("Có tổng cộng: " . count($trackingCode) . " tracking sẽ được chạy." . PHP_EOL);
        foreach ($trackingCode as $tracking) {
            if (!$tracking->tracking_code) {
                $this->stdout("Không có tracking code...." . PHP_EOL);
                continue;
            }
            $this->stdout($tracking->tracking_code . PHP_EOL);
            /** @var DraftExtensionTrackingMap[] $exts */
            $exts = DraftExtensionTrackingMap::find()
                ->where('tracking_code like \'%'.$tracking->tracking_code.'\'')
                ->andWhere(['status' => DraftExtensionTrackingMap::STATUST_NEW])
                ->all();
            if($exts){
                foreach ($exts as $ext){
                    /** @var DraftDataTracking $draft_data */
                    $draft_data = DraftDataTracking::find()->where([
                        'tracking_code' => $tracking->tracking_code,
                        'manifest_id' => $tracking->manifest_id,
                        'manifest_code' => $tracking->manifest_code,
                        'order_id' => $ext->order_id,
                        'product_id' => $ext->product_id,
                        'purchase_invoice_number' => $ext->purchase_invoice_number,
                    ])->one();
                    if (!$draft_data) {
                        $draft_data = new DraftDataTracking();
                        $data = $ext->getAttributes();
                        unset($data['id']);
                        $draft_data->setAttributes($data,false);
                        $draft_data->tracking_code = strtolower($tracking->tracking_code);
                        $draft_data->manifest_id = $tracking->manifest_id;
                        $draft_data->manifest_code = $tracking->manifest_code;
                        $draft_data->created_at = time();
                        $draft_data->updated_at = time();
                        $draft_data->status = DraftDataTracking::STATUS_CHECK_DONE;
                        $draft_data->tracking_merge = strtolower($tracking->tracking_code);
                        $draft_data->tracking_merge .= strtolower($tracking->tracking_code) == strtolower($ext->tracking_code) ? '' : ','.strtolower($ext->tracking_code);
                        $draft_data->save(0);
                    }
                    $ext->draft_data_tracking_id = $draft_data->id;
                    $ext->status = DraftExtensionTrackingMap::JOB_CHECKED;
                    $ext->save(0);
                }
            }else{
                $draft_data_one = new DraftDataTracking();
                $draft_data_one->tracking_code = $tracking->tracking_code;
                $draft_data_one->manifest_id = $tracking->manifest_id;
                $draft_data_one->manifest_code = $tracking->manifest_code;
                $draft_data_one->created_at = time();
                $draft_data_one->updated_at = time();
                $draft_data_one->status = DraftDataTracking::STATUS_MAKE_US_SENDING;
                $draft_data_one->tracking_merge = strtolower($tracking->tracking_code);
                $draft_data_one->save(0);
            }
            $tracking->status_merge = \common\models\TrackingCode::STATUS_MERGE_DONE;
            $tracking->save(0);
        }
        $manifest->status = Manifest::STATUS_EMPTY;
        if(count($trackingCode)){
            $manifest->status = Manifest::STATUS_MERGE_DONE;
        }
        $manifest->save(0);
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
                            $ext->status = DraftExtensionTrackingMap::STATUST_NEW;
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
        /** @var DraftDataTracking[] $track */
        $track = DraftDataTracking::find()
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
            $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn').'/test/callback-boxme';
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
        $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn')."/v1/cart";
        $data['sku'] = "302899518304";
        $data['seller'] = "sasy420";
        $data['quantity'] = "1";
        $data['source'] = "ebay";
        $data['image'] = 'https://i.ebayimg.com/00/s/MTI0OFgxNDI1/z/DaQAAOSwn1Fbq-H2/$_12.JPG';
        $data['parentSku'] = "302899518304";
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
            if ($dta['success']) {
                $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn')."/v1/check-out";
                $data_2['cartIds'] = $dta['data']['key'];
                $data_2['paymentProvider'] = 1;
                $data_2['paymentMethod'] = 1;
                $data_2['bankCode'] = null;
                $data_2['couponCode'] = null;
                $client = new \yii\httpclient\Client();
                $request = $client->createRequest();
                $request->setFullUrl($url);
                $request->addHeaders([
                    'X-Access-Token' => $token
                ]);
                $request->setFormat('json');
                $request->setMethod('POST');
                $request->setData($data_2);
                $response = $client->send($request);
                if($response->isOk){
                    $dta_2 = json_decode($response->getContent(), true);
                    $order_id = $dta_2['data']['order']['id'];
                    $order = Order::findOne($order_id);
                    if($order){
                        Product::updateAll(['id' => $product_id],['order_id' => $order->id]);
                        ProductFee::updateAll(['product_id' => $product_id],['order_id' => $order->id]);
                        return $order;
                    }
                }
            }
        }
        return false;
    }

    public function actionParserPackage(){
        /** @var DraftPackageItem[] $draft_package */
        $draft_package = DraftPackageItem::find()
            ->with(['manifest'])
            ->where(['<>' ,'status' , DraftPackageItem::STATUS_PARSER])
            ->limit(500)->all();
        foreach ($draft_package as $packageItem){
            $package = Package::find()
                ->where(['manifest_code' => $packageItem->manifest_code])
                ->andWhere([
                    'or',
                    ['like','tracking_seller',$packageItem->tracking_code],
                    ['like','tracking_reference_1',$packageItem->tracking_code],
                    ['like','tracking_reference_2',$packageItem->tracking_code]
                ])->orderBy('id desc')->one();
            if(!$package){
                $package = new Package();
                $package->tracking_seller = $packageItem->tracking_code;
                $package->manifest_code = $packageItem->manifest_code;
                $package->package_weight = 0;
                $package->stock_in_local = $packageItem->created_at;
                $package->current_status = "STOCK_IN_LOCAL";
                $package->manifest_code = $packageItem->manifest_code;
                $package->warehouse_id = $packageItem->manifest->receive_warehouse_id;
                $package->created_at = time();
                $package->remove = 0;
                $package->version = 'v4.0';
                $package->save(false);
                $package->package_code = TextUtility::GeneratePackingCode($package->id);
            }
            $package->order_ids .= $package->order_ids ? ','.$packageItem->order_id : ''.$packageItem->order_id;
            $package->package_weight += $packageItem->weight;
            $package->updated_at = time();
            $package->save(0);
            /** @var PackageItem $item */
            $item = PackageItem::find()->where(['box_me_warehouse_tag' => $packageItem->warehouse_tag_boxme,'remove' => 0])->one();
            if(!$item){
                $item = new PackageItem();
                $item->created_at = time();
            }
            $item->package_id = $package->id;
            $item->package_code = $package->package_code;
            $item->box_me_warehouse_tag = $packageItem->warehouse_tag_boxme;
            $item->order_id = $packageItem->order_id;
            $item->sku = $packageItem->order_id;
            $item->quantity = $packageItem->quantity;
            $item->weight = $packageItem->weight;
            $item->change_weight = ($packageItem->dimension_l*$packageItem->dimension_h*$packageItem->dimension_w)/5;
            $item->dimension_l = $packageItem->dimension_l;
            $item->dimension_h = $packageItem->dimension_h;
            $item->dimension_w = $packageItem->dimension_w;
            $item->stock_in_local = $packageItem->created_at;
            $item->current_status = "STOCK_IN_LOCAL";
            $item->updated_at = time();
            $item->remove = 0;
            $item->version = "v4.0";
            $item->save(0);
        }
    }
    public function actionPurchase(){
        /** @var PurchaseOrder[] $purchase_o */
        $purchase_o = PurchaseOrder::find()->all();
        foreach ($purchase_o as $item){
            $wh = Warehouse::findOne($item->receive_warehouse_id);
            if($wh){
                PurchaseProduct::updateAll(
                    ['receive_warehouse_id' => $wh->id,'receive_warehouse_name' => $wh->name],
                    ['purchase_order_id' => $item->id]
                );
            }
        }
    }
    public function actionGetTypeTracking(){
        $this->stdout('Bắt đầu lấy kiểu tracking ...'.PHP_EOL);
        $manifest = Manifest::find()->where(['status' => Manifest::STATUS_MERGE_DONE])->limit(1)->one();
        if(!$manifest){
            $this->stdout('Không có lô nào'.PHP_EOL);
            die;
        }
        $manifest->status = Manifest::STATUS_TYPE_GETTING;
        $manifest->save(0);
        $tracking = DraftDataTracking::find()
            ->where(['manifest_id' => $manifest->id])
            ->select('count(id) as `countId`, tracking_code')
            ->groupBy('tracking_code')->asArray()->all();
        if(!$tracking){
            $this->stdout('Không có Tracking nào'.PHP_EOL);
            die;
        }
        DraftDataTracking::updateAll(
            ['type_tracking' => DraftDataTracking::TYPE_NORMAL],
            ['manifest_id' => $manifest->id]
        );
        foreach ($tracking as $dataTracking){
            if($dataTracking['countId'] > 1){
                DraftDataTracking::updateAll(
                    ['type_tracking' => DraftDataTracking::TYPE_SPLIT],
                    [
                        'manifest_id' => $manifest->id,
                        'tracking_code' => $dataTracking['tracking_code']
                    ]
                );
            }
        }
        DraftDataTracking::updateAll(
            ['type_tracking' => DraftDataTracking::TYPE_UNKNOWN],
            [   'and',
                ['manifest_id' => $manifest->id],
                ['or',['product_id' => null],['product_id' => '']],
                ['or',['order_id' => null],['order_id' => '']],
            ]
        );
        $manifest->status = Manifest::STATUS_TYPE_GET_DONE;
        $manifest->save(0);
        $this->stdout('Đã lấy xong'.PHP_EOL);
    }
    public function actionEmulatorCallbackDetail($manifest_id){
        $manifest = Manifest::findOne($manifest_id);
        if(!$manifest){
            $this->stdout('Không tìm thấy lô có id là '.$manifest_id);
            die;
        }
        $this->stdout('Bắt đầu chạy ... '.PHP_EOL);
        $code = $manifest->manifest_code.'-'.$manifest_id;
        $pageTotal = 2;
        for ($page = 1; $page <= $pageTotal; $page++) {
            $data = BoxMeClient::GetDetail($code, $page, 'vn');
            $url = 'https://wms.boxme.asia/v1/packing/detail/' . $code . '/?page=' . $page;
            if ($data['success']) {
                if ($page == 1) $pageTotal = $data['total_page'];
                echo "Tổng tracking " . $data['total_item'] . "." . PHP_EOL;
                echo "Tổng trang " . $data['total_page'] . "." . PHP_EOL;
                echo "trang " . $page . "." . PHP_EOL;
                foreach ($data['results'] as $result) {
                    $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn').'/test/callback-boxme';
                    $client = new \yii\httpclient\Client();
                    $request = $client->createRequest();
                    $request->setFullUrl($url);
                    $request->setFormat('json');
                    $request->setMethod('POST');
                    $request->setData($result);
                    $response = $client->send($request);
                    if (!$response->isOk) {
                        $res = $response->getData();
                        echo $res['messages'].PHP_EOL;
//                return $res['messages'];
                    }
                    $res = $response->getData();
                }
            } else {
                echo "Lỗi gọi api" . PHP_EOL;
                echo $data['message'] . PHP_EOL;
               die;
            }
        }
    }
}
