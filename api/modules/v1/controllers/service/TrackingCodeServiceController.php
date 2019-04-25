<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftPackageItem;
use common\models\draft\DraftWastingTracking;
use common\models\Package;
use common\models\PackageItem;
use common\models\Product;
use common\models\PurchaseProduct;
use Yii;

class TrackingCodeServiceController extends BaseApiController
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
            'split-tracking' => ['DELETE'],
            'merge' => ['POST'],
            'map-unknown' => ['POST'],
            'mark-hold' => ['POST'],
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
        || !$missing || $missing->status == DraftDataTracking::STATUS_CALLBACK_SUCCESS
        || $wasting->status == DraftDataTracking::STATUS_MERGE_WAST
        ){
            return $this->response(false,'data merge not incorrect!');
        }
        $model = DraftPackageItem::find()->where([
            'tracking_code' => $missing->tracking_code,
            'status' => DraftPackageItem::STATUS_SPLITED,
            'product_id' => $missing->product_id ? $missing->product_id : $wasting->product_id,
            'order_id' => $missing->order_id ? $missing->order_id : $wasting->order_id
        ])->one();
        if(!$model){
            $model = new DraftPackageItem();
            $model->tracking_code = $missing->tracking_code;
            $model->tracking_merge = $missing->tracking_code.','.$wasting->tracking_code;
            $model->product_id = $missing->product_id ? $missing->product_id : $wasting->product_id;
            $model->order_id = $missing->order_id ? $missing->order_id : $wasting->order_id;
            $model->created_at = time();
            $model->created_by = Yii::$app->user->getId();
        }
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
        $missing->status = DraftDataTracking::STATUS_MERGE_WAST;
        $missing->save();
        $wasting->status = DraftWastingTracking::MERGE_MANUAL;
        $wasting->save();
        return $this->response(true,'Merge success!');
    }
    public function actionMapUnknown($id){
        $model = DraftPackageItem::findOne($id);
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
        /** @var DraftPackageItem $model */
        $model = DraftPackageItem::find()->where(['id' => $id])->active()->one();
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        $model->status = DraftPackageItem::STATUS_SPLITED;
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
        /** @var DraftPackageItem $model */
        $model = DraftPackageItem::findOne($id);
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
        $model = DraftPackageItem::findOne($id);
        $model->hold = $this->post['hold'];
        $model->save(0);
        /** @var Package $pack */
        $pack = Package::find()->where(['tracking_seller' => $model->tracking_code,'manifest_code' => $model->manifest_code])->one();
        if($pack){
            PackageItem::updateAll(
                ['hold' => $this->post['hold']],
                [
                    'package_id' => $pack->id,
                    'sku' => $model->product ? strtolower($model->product->portal) == 'ebay' ? $model->product->parent_sku : $model->product->sku : null,
                    'updated_at' => time()
                ]
            );
        }
        return $this->response(true, $this->post['hold'] ? 'hold success!' : 'UnHold success!');
    }
}