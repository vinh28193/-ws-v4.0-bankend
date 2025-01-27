<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\components\boxme\BoxMeClient;
use common\components\lib\TextUtility;
use common\logs\PackingLogs;
use common\logs\TrackingLogs;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftExtensionTrackingMap;
use common\models\Order;
use common\models\Package;
use common\models\draft\DraftWastingTracking;
use common\models\Manifest;
use common\models\Product;
use common\models\PurchaseProduct;
use common\models\TrackingCode;
use Yii;
use yii\helpers\ArrayHelper;

class ServiceUsSendingController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['merge', 'index', 'map-unknown', 'split-tracking', 'seller-refund','mark-hold'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'get-type' => ['PUT'],
            'merge' => ['POST'],
            'map-unknown' => ['POST'],
            'insert-tracking' => ['POST'],
            'seller-refund' => ['POST'],
            'index' => ['GET'],
        ];
    }
    public function actionMapUnknown($id){
        $model = DraftDataTracking::findOne($id);
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        $product = Product::findOne($this->post['product_id']);
        if(!$product){
            return $this->response(false,'Cannot find your product id!');
        }
        $count = DraftDataTracking::find()->where(['tracking_code' => $model->tracking_code])->count();
        $model->product_id = $product->id;
        $model->order_id = $product->order_id;
        $model->save();
        $product->updateStockinUs($model->stock_in_us);
        $product->updateStockoutUs($model->stock_out_us);
        $product->save(false);
        $product->order->updateStockinUs($model->stock_in_us);
        $product->order->updateStockoutUs($model->stock_out_us);
        $product->order->save(false);
        $logTracking = new TrackingLogs();
        $logTracking->current_status = TrackingLogs::STATUS_STOCK_OUT_US;
        $logTracking->type_log = TrackingLogs::TRACKING_MAP_PRODUCT;
        $logTracking->tracking_code = $model->tracking_code;
        $logTracking->message_log = 'Map tracking '.$model->tracking_code.' với product: ' .$product->id . ', order: '.$product->order->ordercode;
        $logTracking->message_log .= '<br>Loại tracking chuyển từ '.$model->type_tracking.' sang ';
        if($count > 1){
            DraftDataTracking::updateAll(
                ['type_tracking' => DraftDataTracking::TYPE_SPLIT],
                ['tracking_code' => $model->tracking_code]
            );
            $logTracking->message_log .= DraftDataTracking::TYPE_SPLIT;
        }else{
            $model->type_tracking = DraftDataTracking::TYPE_NORMAL;
            $model->save(0);
            $logTracking->message_log .= DraftDataTracking::TYPE_NORMAL;
        }
        $model->refresh();
        $logTracking->more_data = $model->getAttributes();
        $logTracking->save();
        return $this->response(true,'Map tracking success!');
    }
    public function actionGetType($id){
        $manifest = Manifest::findOne($id);
        if(!$manifest){
            return $this->response(false,"Cannot find manifest ".$id);
        }
        $tracking = DraftDataTracking::find()
            ->where(['manifest_id' => $manifest->id])
            ->select('count(id) as `countId`, tracking_code')
            ->groupBy('tracking_code')->asArray()->all();
        if(!$tracking){
            return $this->response(false,"Tracking is empty with manifest ".$manifest->manifest_code.'-'.$id);
        }
        $manifest->status = Manifest::STATUS_TYPE_GETTING;
        $manifest->save(0);
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
        return $this->response(True,"Re Get Type manifest ".$manifest->manifest_code.'-'.$id. ' success!');
    }

    public function actionSellerRefund($id)
    {
        $model = DraftDataTracking::findOne($id);
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
//        $model->status = 'SELLER_'.strtoupper($this->post['type']);
//        $model->save(0);
        $model->product->seller_refund_amount = $model->product->seller_refund_amount ? floatval($model->product->seller_refund_amount) + $this->post['amount'] : $this->post['amount'];
        $model->product->save(0);
        if($model->purchaseOrder){
            /** @var PurchaseProduct $proPurchase */
            $proPurchase = PurchaseProduct::find()->where(['purchase_order_id' => $model->purchaseOrder->id, 'product_id' => $model->product_id])->one();
            $proPurchase->seller_refund_amount = $proPurchase->seller_refund_amount ? floatval($proPurchase->seller_refund_amount) + $this->post['amount'] :  $this->post['amount'];
            $proPurchase->save(0);
        }
        $model->seller_refund_amount = $model->seller_refund_amount ? floatval($model->seller_refund_amount) + $this->post['amount'] :  $this->post['amount'];
        $model->save(0);
        Package::updateAll(['seller_refund_amount' => $model->seller_refund_amount],['draft_data_tracking_id' => $model->id]);
        return $this->response(true,'Update seller refund '.$this->post['type'].' success!');
    }
    public function actionMerge(){
        $type = Yii::$app->request->post('type','tracking');
        if(!isset($this->post['data_id']) || !isset($this->post['data_tracking_code']) || !$this->post['data_id'] || !($this->post['data_tracking_code'])){
            return $this->response(false,'data merge empty');
        }
        if(!isset($this->post['ext_id']) || !isset($this->post['ext_tracking_code']) || !$this->post['ext_id'] || !($this->post['ext_tracking_code'])){
            return $this->response(false,'data merge empty');
        }
        if($type == 'tracking'){
            $data = DraftDataTracking::findOne($this->post['data_id']);
        }elseif ($type == 'package'){
            $data = Package::findOne($this->post['data_id']);
        }else{
            return $this->response(false,'Cannot found type '.$type.' !');
        }
        $ext = DraftExtensionTrackingMap::findOne($this->post['ext_id']);
        if(!$data || $data->type_tracking != DraftDataTracking::TYPE_UNKNOWN || $data->product_id || $data->order_id
            || !$ext
            || $ext->status == DraftExtensionTrackingMap::JOB_CHECKED
            || $ext->status == DraftExtensionTrackingMap::MAPPED
        ){
            return $this->response(false,'data merge not incorrect!');
        }
        $data->product_id = $ext->product_id;
        $data->order_id = $ext->order_id;
        $data->quantity = $ext->quantity;
        $data->purchase_invoice_number = $ext->purchase_invoice_number;
        $data->tracking_merge = $data->tracking_merge . ','.strtoupper($ext->tracking_code);
        $ext->status = DraftExtensionTrackingMap::MAPPED;
        $ext->draft_data_tracking_id = $type == 'tracking' ? $data->id : $data->draft_data_tracking_id;
        $ext->save(0);
        if($ext->product){
            $ext->product->updateStockinUs($data->stock_in_us);
            $ext->product->updateStockoutUs($data->stock_out_us);
            $ext->product->save(false);
        }
        if($ext->order){
            $ext->order->updateStockinUs($data->stock_in_us);
            $ext->order->updateStockoutUs($data->stock_out_us);
            $ext->order->save(false);
        }
        $count = DraftDataTracking::find()->where(['tracking_code' => $data->tracking_code])->count();
        $logTracking = new TrackingLogs();
        $logTracking->current_status = TrackingLogs::STATUS_STOCK_OUT_US;
        $logTracking->type_log = TrackingLogs::TRACKING_MERGE_TRACKING_SELLER;
        $logTracking->tracking_code_ws = $data->ws_tracking_code;
        $logTracking->tracking_code = $data->tracking_code;
        $logTracking->tracking_code_reference = $ext->tracking_code;
        $logTracking->message_log = 'Gộp tracking '.$data->tracking_code.'' .
            ' và tracking seller '.$ext->tracking_code.' thành tracking '.$data->tracking_code.'.' .
            'Và chuyển loại tracking từ : '.$data->type_tracking.' thành: ' ;
        $messagePackage = 'Map Package '.$data->package_code.'' .
            ' và tracking seller '.$ext->tracking_code.'.' .
            'Và chuyển loại tracking từ : '.$data->type_tracking.' thành: ';
        if($count > 1){
            $data->type_tracking = DraftDataTracking::TYPE_SPLIT;
            DraftDataTracking::updateAll(
                ['type_tracking' => DraftDataTracking::TYPE_SPLIT],
                ['tracking_code' => $data->tracking_code]
            );
        }else{
            $data->type_tracking = DraftDataTracking::TYPE_NORMAL;
        }
        $data->save(0);
        $logTracking->message_log .= $data->type_tracking;
        $logTracking->more_data = $data->getAttributes();
        $logTracking->save();
        if($type == 'package'){
            $logPacking = new PackingLogs();
            $logPacking->package_code = $data->package_code;
            $logPacking->type_log = PackingLogs::PACKING_MERGE_TRACKING_SELLER;
            $logPacking->tracking_code_reference = $ext->tracking_code;
            $logPacking->message_log =  $messagePackage.$data->type_tracking;
            $logPacking->more_data = $data->getAttributes();
            $logPacking->save();
        }
        return $this->response(true,'Merge success!');
    }

    public function actionInsertTracking(){
        if(!$this->post['tracking_code'] || !$this->post['info']){
            return $this->response(false,'Please fill all field require!');
        }
        $count = 0;
        $trackingCode = TrackingCode::findOne(['tracking_code' => $this->post['tracking_code']]);
        if(!$trackingCode){
            $trackingCode = new TrackingCode();
            $trackingCode->tracking_code = $this->post['tracking_code'];
        }
        $order_ids = explode(',',$trackingCode->order_ids);
        $product_ids = explode(',',$trackingCode->product_ids);
        $dataShipment = [];
        foreach ($this->post['info'] as $info){
            $datumShipment = [];
            $order_id = ArrayHelper::getValue($info,'order_id');
            $product_id = ArrayHelper::getValue($info,'product_id');
            $quantity = ArrayHelper::getValue($info,'quantity');
            $purchase_number_invoice = ArrayHelper::getValue($info,'purchase_number_invoice');
            if($product_id && $quantity){
                $product = Product::findOne($product_id);
                $order = $product->order;
                if($product && (!$order_id || $order_id == $product->order_id) && $order){
                    if(BoxMeClient::SyncProduct($product)){
                        if(in_array($product->id,$product_ids)){
                            $product_ids[] = $product->id;
                        }
                        if(in_array($product->order_id,$order_ids)){
                            $order_ids[] = $product->order_id;
                        }
                        $datumShipment['sku'] = $product->sku;
                        $datumShipment['quantity'] = $quantity;
                        $datumShipment['img_check'] = 1;
                        $datumShipment['description'] = $product->product_name;
                        $datumShipment['bsin'] = TextUtility::GenerateBSinBoxMe($product->id);
                        $trackingCode->quantity = $trackingCode->quantity ? $trackingCode->quantity + $quantity : $quantity;
                        $order->updateSellerShipped();
                        $order->save(false);
                        $product->updateSellerShipped();
                        $product->save(false);
                        $model = new DraftExtensionTrackingMap();
                        $model->tracking_code = $this->post['tracking_code'];
                        $model->order_id = $product->order_id;
                        $model->product_id = $product->id;
                        $model->quantity = $quantity;
                        $model->purchase_invoice_number = $purchase_number_invoice;
                        $model->status = DraftExtensionTrackingMap::STATUST_NEW;
                        $model->created_by = Yii::$app->user->getId();
                        $model->updated_by = Yii::$app->user->getId();
                        $model->created_at = time();
                        $model->updated_at = time();
                        $model->save(0);
                        $logTracking = new TrackingLogs();
                        $logTracking->tracking_code = $model->tracking_code;
                        $logTracking->message_log = 'Tạo Tracking Seller '.$model->tracking_code.' cho product '.$product->id.' của order '.$product->order->ordercode;
                        $logTracking->type_log = TrackingLogs::TRACKING_CREATE;
                        $logTracking->current_status = TrackingLogs::STATUS_SELLER_SHIPPED;
                        $logTracking->product_id = $product->id;
                        $logTracking->order_id = $product->order_id;
                        $logTracking->more_data = $model->getAttributes();
                        $logTracking->save();
                        $params = array(
                            'manifest'=>null,
                            'trackingcode' => $model->tracking_code,
                            'text' => 'Người bán gửi',
                            'log_type' => 'Seller Shipped',
                            'store' => null,
                            'boxme_warehosue' => null
                        );

                        Yii::$app->wsLog->push('TrackingLog','Seller Shipped','Người bán gửi hàng',$params);
                        $count ++;
                        $dataShipment[] = $datumShipment;
                    }
                }
            }
        }
//        $trackingCode-> = implode(',',$product_ids);
        $trackingCode->product_ids = $product_ids && count($product_ids) ? implode(',',$product_ids) : '';
        $trackingCode->order_ids = $order_ids && count($order_ids) ?  implode(',',$order_ids) : '';
        $trackingCode->status = $trackingCode->status ? $trackingCode->status : TrackingCode::STATUS_MERGE_NEW;
        $trackingCode->save(false);
        if($dataShipment && count($dataShipment)){
//            $response = BoxMeClient::CreateLiveShipment($dataShipment,$trackingCode->tracking_code);
//        $trackingCode->save(false);
        }
        return $this->response(true,'Insert tracking to '.$count.' product success!');
    }
}