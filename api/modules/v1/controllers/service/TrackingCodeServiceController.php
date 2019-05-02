<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\helpers\WeshopHelper;
use common\models\DeliveryNote;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftWastingTracking;
use common\models\Package;
use common\models\PackageItem;
use common\models\Product;
use common\models\PurchaseProduct;
use common\models\Shipment;
use Yii;

class TrackingCodeServiceController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['merge', 'index', 'map-unknown', 'split-tracking', 'seller-refund','mark-hold','insert-shipment'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'split-tracking' => ['DELETE'],
            'merge' => ['POST'],
            'map-unknown' => ['POST'],
            'mark-hold' => ['POST'],
            'insert-shipment' => ['POST'],
            'seller-refund' => ['POST'],
            'index' => ['GET'],
        ];
    }
    public function actionMerge(){
        if(!isset($this->post['merge']) || !isset($this->post['merge']['data']) || !$this->post['merge']['data'] || !isset($this->post['merge']['type']) || !$this->post['merge']['type']){
            return $this->response(false,'data merge empty');
        }
        if(!isset($this->post['target']) || !isset($this->post['target']['data']) || !$this->post['target']['data'] || !isset($this->post['target']['type']) || !$this->post['target']['type']){
            return $this->response(false,'data target empty');
        }
        $missing = $this->post['merge']['type'] == 'miss' ? DraftDataTracking::findOne($this->post['merge']['data']['id']) : DraftDataTracking::findOne($this->post['target']['data']['id']);
        $wasting = $this->post['merge']['type'] == 'wast' ? DraftWastingTracking::findOne($this->post['merge']['data']['id']) : DraftWastingTracking::findOne($this->post['target']['data']['id']);
        if(!$wasting || $wasting->status == DraftWastingTracking::MERGE_MANUAL
            || $wasting->status == DraftWastingTracking::MERGE_CALLBACK
        || !$missing || $missing->status == DraftDataTracking::STATUS_LOCAL_INSPECTED
            || $missing->status == DraftDataTracking::STATUS_LOCAL_RECEIVED
        ){
            return $this->response(false,'data merge not incorrect!');
        }

        $model = new Package();
        $model->tracking_code = $missing->tracking_code;
        $model->tracking_merge = $missing->tracking_code . ',' . $wasting->tracking_code;
        $model->product_id = $missing->product_id ? $missing->product_id : $wasting->product_id;
        $model->order_id = $missing->order_id ? $missing->order_id : $wasting->order_id;
        $model->created_at = time();
        $model->created_by = Yii::$app->user->getId();
        $model->quantity = $wasting->quantity;
        $model->weight = $wasting->weight;
        $model->dimension_l = $wasting->dimension_l;
        $model->dimension_h = $wasting->dimension_h;
        $model->dimension_w = $wasting->dimension_w;
        $model->manifest_id = $missing->manifest_id;
        $model->manifest_code = $missing->manifest_code;
        $model->purchase_invoice_number = $missing->purchase_invoice_number ? $missing->purchase_invoice_number : $wasting->purchase_invoice_number;
        $model->status = $wasting->status;
        $model->updated_at = time();
        $model->updated_by = Yii::$app->user->getId();
        $model->item_name = $wasting->item_name;
        $model->warehouse_tag_boxme = $wasting->warehouse_tag_boxme;
        $model->note_boxme = $wasting->note_boxme;
        $model->image = $wasting->image;
        $model->save();
        $missing->status = DraftDataTracking::STATUS_LOCAL_INSPECTED;
        $missing->save();
        $wasting->status = DraftWastingTracking::MERGE_MANUAL;
        $wasting->save();
        return $this->response(true,'Merge success!');
    }
    public function actionMapUnknown($id){
        $model = Package::findOne($id);
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        $product = Product::findOne($this->post['product_id']);
        if(!$product){
            return $this->response(false,'Cannot find your product id!');
        }
        $model->product_id = $product->id;
        $model->order_id = $product->order_id;
        $model->item_name = $model->item_name && strtolower($model->item_name) != 'none' ? $model->item_name : $product->product_name;
        $model->save();
        return $this->response(true,'Map tracking success!');
    }
    public function actionSplitTracking($id){
        /** @var Package $model */
        $model = Package::find()->where(['id' => $id])->active()->one();
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        if($model->shipment_id){
            return $this->response(false,'Package '.$model->id.' was in a shipment!');
        }

        $model->status = Package::STATUS_SPLITED;
        $model->remove = 0;
        $model->save(0);
        $arr_tracking = explode(',',$model->tracking_merge);
        foreach ($arr_tracking as $k => $v){
            if($k == 1){
                /** @var DraftWastingTracking $class */
                $class = DraftWastingTracking::className();
            }else{
                /** @var DraftMissingTracking $class */
                $class = DraftMissingTracking::className();
            }
            $tmp =  $class::find()->where([
                'status' => $class::MERGE_MANUAL,
                'tracking_code' => $v
            ])->one();
            if(!$tmp){
                $tmp = new $class();
            }
            $data = $model->getAttributes();
            unset($data['id']);
            $tmp->setAttributes($data,false);
            $tmp->status = $class::SPILT_MANUAL;
            $tmp->tracking_code = $v;
            if($k == 1){
                $tmp->product_id = '';
                $tmp->order_id = '';
            }else{
                $tmp->item_name = '';
                $tmp->weight = '';
                $tmp->dimension_l = '';
                $tmp->dimension_h = '';
                $tmp->dimension_w = '';
                $tmp->image = '';
            }
            $tmp->save(0);
        }
        return $this->response(true,'Split success!');
    }

    public function actionSellerRefund($id)
    {
        /** @var Package $model */
        $model = Package::findOne($id);
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
        DraftDataTracking::updateAll(['seller_refund_amount' => $model->seller_refund_amount],['id' => $model->draft_data_tracking_id]);
        return $this->response(true,'Update seller refund '.$this->post['type'].' success!');
    }
    public function actionMarkHold($id){
        $model = Package::findOne($id);
        $model->hold = $this->post['hold'];
        $model->save(0);
        /** @var DeliveryNote $pack */
        $pack = DeliveryNote::find()->where(['tracking_seller' => $model->tracking_code,'manifest_code' => $model->manifest_code])->one();
        if($pack){
            Shipment::updateAll(
                ['is_hold' => $this->post['hold']],
                [
                    'id' => $pack->shipment_id,
                ]
            );
        }
        return $this->response(true, $this->post['hold'] ? 'hold success!' : 'UnHold success!');
    }
    public function actionInsertShipment(){
        $isCreateAll = false;
        $qr = Package::find()->with(['order', 'manifest']);
        if(isset($this->post['listCheck']) && $this->post['listCheck']){
            $qr->where(['id' => $this->post['listCheck']]);
        }else{
            if(isset($this->post['manifest_id']) && $this->post['manifest_id']){
                $qr->where(['manifest_id' => $this->post['manifest_id']]);
                $isCreateAll = true;
            }else{
                return $this->response(false, 'Cannot find package!');
            }
        }
        /** @var Package[] $packages */
        $packages = $qr->orderBy('id desc')->all();
        if(!$packages){
            return $this->response(false, 'Cannot find package!');
        }
        $list_id = [];
        if(!$isCreateAll){
            $shipment = new Shipment();
            $shipment->version = '4.0';
            $shipment->shipment_status = Shipment::STATUS_NEW;
            $deliverynote = new DeliveryNote();
        }
        $listHold = [];
        foreach ($packages as $package){
            if($package->hold){
                return $this->response(false, 'DeliveryNote '.$package->id .' is hold!');
                break;
            }
            if($package->shipment_id){
                return $this->response(false, 'DeliveryNote '.$package->id .' was in a shipment!');
                break;
            }
            if($package->remove){
                continue;
            }
            if($isCreateAll){
                $shipment = new Shipment();
                $shipment->version = '4.0';
                $shipment->shipment_status = Shipment::STATUS_NEW;
                $deliverynote = new DeliveryNote();
            }
            $list_id[] = $package->id;
            $deliverynote->current_status = DeliveryNote::STATUS_STOCK_IN_LOCAL;
            $deliverynote->stock_in_local = $package->stock_in_local;
            $deliverynote->manifest_code = $package->manifest_code;
            $deliverynote->tracking_seller = !$deliverynote->tracking_seller ? $package->tracking_code : '';
            $deliverynote->tracking_reference_1 = !$deliverynote->tracking_reference_1
                                                && $deliverynote->tracking_seller != $package->tracking_code
                                                ? $package->tracking_code : '';
            $deliverynote->tracking_reference_2 = !$deliverynote->tracking_reference_1 && $deliverynote->tracking_reference_2
                                                && $deliverynote->tracking_reference_1 != $package->tracking_code
                                                && $deliverynote->tracking_seller != $package->tracking_code
                                                ? $package->tracking_code : '';
            $deliverynote->warehouse_id = $package->manifest->receive_warehouse_id;
            $deliverynote->created_at = time();
            $deliverynote->updated_at = time();
            $deliverynote->remove = 0;
            $deliverynote->version = '4.0';

            $shipment->warehouse_tags = $shipment->warehouse_tags ? $shipment->warehouse_tags .',' .$package->warehouse_tag_boxme : $shipment->warehouse_tags ;
            $shipment->total_weight = $shipment->total_weight ? $shipment->total_weight + $package->weight : $package->weight;
            $shipment->customer_id = $package->order->customer_id;
            $shipment->receiver_email = $package->order->receiver_email;
            $shipment->receiver_name = $package->order->receiver_name;
            $shipment->receiver_phone = $package->order->receiver_phone;
            $shipment->receiver_address = $package->order->receiver_address;
            $shipment->receiver_country_id = $package->order->receiver_country_id;
            $shipment->receiver_country_name = $package->order->receiver_country_name;
            $shipment->receiver_province_id = $package->order->receiver_province_id;
            $shipment->receiver_province_name = $package->order->receiver_province_name;
            $shipment->receiver_district_id = $package->order->receiver_district_id;
            $shipment->receiver_district_name = $package->order->receiver_district_name;
            $shipment->note_by_customer = $package->order->note_by_customer;
            $shipment->total_price = $shipment->total_price ? $shipment->total_price + $package->price : $package->price;
            $shipment->total_cod = $shipment->total_cod ? $shipment->total_cod + $package->cod : $package->cod;
            $shipment->total_quantity = $shipment->total_quantity ? $shipment->total_quantity + $package->quantity : $package->quantity;
            if($isCreateAll){
                $shipment->save(0);
                $deliverynote->shipment_id = $shipment->id;
                $deliverynote->save(0);
                $deliverynote->delivery_note_code = WeshopHelper::generateTag($deliverynote->id,'WSVNDN', 16);
                $deliverynote->save(0);
                $package->shipment_id = $shipment->id;
                $package->delivery_note_code = $deliverynote->delivery_note_code;
                $package->delivery_note_id = $deliverynote->id;
                $package->save(0);
            }
        }
        if(!$isCreateAll){
            $shipment->save(0);
            $deliverynote->shipment_id = $shipment->id;
            $deliverynote->save(0);
            $deliverynote->delivery_note_code = WeshopHelper::generateTag($deliverynote->id,'WSVNDN', 16);
            $deliverynote->save(0);
            Package::updateAll([
                'shipment_id' => $shipment->id,
                'delivery_note_code' => $deliverynote->delivery_note_code,
                'delivery_note_id' => $deliverynote->id,
            ],['id' => $list_id]);
        }
        return $this->response(true, 'Create Shipment success! Note: Package hold ('.implode(',',$listHold).') will not be created!');
    }
}