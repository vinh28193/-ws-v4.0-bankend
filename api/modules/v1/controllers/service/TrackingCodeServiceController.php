<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftPackageItem;
use common\models\draft\DraftWastingTracking;
use common\models\Product;
use Yii;

class TrackingCodeServiceController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['merge','index','map-unknown','split-tracking'],
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
        $missing = $this->post['merge']['type'] == 'miss' ? DraftMissingTracking::findOne($this->post['merge']['data']['id']) : DraftMissingTracking::findOne($this->post['target']['data']['id']);
        $wasting = $this->post['merge']['type'] == 'wast' ? DraftWastingTracking::findOne($this->post['merge']['data']['id']) : DraftWastingTracking::findOne($this->post['target']['data']['id']);
        $model = new DraftPackageItem();
        $model->tracking_code = $missing->tracking_code;
        $model->tracking_merge = $missing->tracking_code.','.$wasting->tracking_code;
        $model->product_id = $missing->product_id ? $missing->product_id : $wasting->product_id;
        $model->order_id = $missing->order_id ? $missing->order_id : $wasting->order_id;
        $model->quantity = $wasting->quantity;
        $model->weight = $wasting->weight;
        $model->dimension_l = $wasting->dimension_l;
        $model->dimension_h = $wasting->dimension_h;
        $model->dimension_w = $wasting->dimension_w;
        $model->manifest_id = $missing->manifest_id;
        $model->manifest_code = $missing->manifest_code;
        $model->purchase_invoice_number = $missing->purchase_invoice_number ? $missing->purchase_invoice_number : $wasting->purchase_invoice_number;
        $model->status = $wasting->status;
        $model->created_at = time();
        $model->updated_at = time();
        $model->created_by = Yii::$app->user->getId();
        $model->updated_by = Yii::$app->user->getId();
        $model->item_name = $wasting->item_name;
        $model->warehouse_tag_boxme = $wasting->warehouse_tag_boxme;
        $model->note_boxme = $wasting->note_boxme;
        $model->image = $wasting->image;
        $model->save();
        $missing->status = DraftMissingTracking::MERGE_MANUAL;
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
        $model = DraftPackageItem::findOne($id);
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        
    }
}