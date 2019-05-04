<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:34 SA
 */

namespace api\controllers;


use common\models\Category;
use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftDataTracking;
use common\models\Package;
use common\models\draft\DraftWastingTracking;
use common\models\TrackingCode;
use yii\base\Controller;
use yii\helpers\ArrayHelper;

class TestController extends Controller
{
    /***Tính Phụ Thu danh Mục**/
    public function actionGetCustomFee()
    {
        $data = [
          'price' => 400, // $ gía gốc mỹ
          'quantity' => 7,
          'weight' => 0.5, // kg
          'isNew' => false,
          'cate_id' => rand(1,20),
        ];

        $category = Category::findOne($data['cate_id']);
        $data['rules'] = "";
        if($category){
            $data['rules'] = json_decode($category->categoryGroup->rule,true);
//            $data['custom_fee'] = $category->getCustomFee();
            $data['message'] = "Lấy custom fee thành công";
            \Yii::$app->response->statusCode = 200;
        }else{
            \Yii::$app->response->statusCode = 400;
            $data['message'] = "Lấy custom fee thất bại";
            $data['custom_fee'] = 0;
        }
        \Yii::$app->response->format = 'json';
        \Yii::$app->response->data = $data;
        \Yii::$app->response->send();
        exit();
        //return parent::actions(); // TODO: Change the autogenerated stub
    }

// call back kiểm hàng
    public function actionCallbackBoxme(){
        $result = \Yii::$app->request->post();
//        print_r($post);
//        die;
        $packing_code = ArrayHelper::getValue($result, 'packing_code');
        $soi = ArrayHelper::getValue($result, 'soi_tracking');
        $tracking = ArrayHelper::getValue($result, 'tracking_code');
        $tracking = strtoupper($tracking);
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
        $manifest = $packing_code ? explode('-',$packing_code) : null;
        $manifest_id = isset($manifest[1]) ? $manifest[1] : null;
        $manifest_code = isset($manifest[0]) ? $manifest[0] : null;
        foreach ($images as $k => $img) {
            if(ArrayHelper::getValue($img, 'urls') && strtolower(ArrayHelper::getValue($img, 'urls')) != 'none'){
                $image .= $k == 0 ? ArrayHelper::getValue($img, 'urls') : ',' . ArrayHelper::getValue($img, 'urls');
            }
        }
        $soi = intval(str_replace('SOI-', '', $soi));
        $soi = $soi ? $soi : null;
        /** @var DraftDataTracking[] $finds */
        $finds = DraftDataTracking::find()->with('product')->where([
            'product_id' => $soi,
            'manifest_id' => $manifest_id,
            'manifest_code' => $manifest_code,
        ])->andWhere(['or','tracking_code like \'%'.$tracking.'\'', ['like','tracking_merge',$tracking]])
            ->all();
        if ($finds) {
            foreach ($finds as $find) {
                if ($find != DraftDataTracking::STATUS_LOCAL_INSPECTED) {
                    $find->status = DraftDataTracking::STATUS_LOCAL_RECEIVED;
                    if(strtolower($status) == 'close'){
                        $find->status = DraftDataTracking::STATUS_LOCAL_INSPECTED;
                        TrackingCode::UpdateStatusTracking($find->tracking_code,$find->status);
                    }
                    $find->item_name = $item_name;
                    $find->save(0);
                    TrackingCode::UpdateStatusTracking($tracking,TrackingCode::STATUS_LOCAL_INSPECTED);
                    $draft = new Package();
                    $draft->tracking_code = $tracking;
                    $draft->manifest_code = $manifest_code;
                    $draft->manifest_id = $manifest_id;
                    $draft->quantity = $quantity;
                    $draft->weight = $weight;
                    $draft->price = $find->product ? $find->product->price_amount_local : 0;
                    $draft->product_id = $soi;
                    $draft->order_id = $find->order_id;
                    $draft->purchase_invoice_number = $find->purchase_invoice_number;
                    $draft->status = $status;
                    $draft->draft_data_tracking_id = $find->id;
                    $draft->seller_refund_amount = $find->seller_refund_amount;
                    $draft->dimension_l = isset($vol[0]) ? $vol[0] : null;
                    $draft->dimension_w = isset($vol[1]) ? $vol[1] : null;
                    $draft->dimension_h = isset($vol[2]) ? $vol[2] : null;
                    $draft->item_name = $item_name;
                    $draft->image = $image;
                    $draft->warehouse_tag_boxme = $tag_code;
                    $draft->note_boxme = $note;
                    $draft->type_tracking = $find->type_tracking;
                    $draft->tracking_merge = strtoupper($find->tracking_code) == strtoupper($tracking) ? $find->tracking_merge : strtoupper($tracking).','.$find->tracking_merge;
                    $draft->createOrUpdate(false);
                    DraftWastingTracking::updateAll([
                        'status' => DraftWastingTracking::MERGE_CALLBACK
                    ], [
                        'tracking_code' => $tracking,
                        'product_id' => $soi,
                        'manifest_id' => $manifest_id,
                        'manifest_code' => $manifest_code,
                    ]);
                }
            }
        } else {
                $wasting = new DraftWastingTracking();
                $wasting->tracking_code = $tracking;
                $wasting->manifest_code = $manifest_code;
                $wasting->manifest_id = $manifest_id;
                $wasting->product_id = $soi;
                $wasting->quantity = $quantity;
                $wasting->weight = $weight;
                $wasting->status = "WAST_CALLBACK";

                $wasting->dimension_l = isset($vol[0]) ? $vol[0] : null;
                $wasting->dimension_w = isset($vol[1]) ? $vol[1] : null;
                $wasting->dimension_h = isset($vol[2]) ? $vol[2] : null;
                $wasting->item_name = $item_name;
                $wasting->image = $image;
                $wasting->warehouse_tag_boxme = $tag_code;
                $wasting->note_boxme = $note;
                $wasting->createOrUpdate(false);
        }
        \Yii::$app->response->format = 'json';
        \Yii::$app->response->data = ['success' => true, 'message' => 'Done'];
        \Yii::$app->response->send();
        exit();
    }
}
