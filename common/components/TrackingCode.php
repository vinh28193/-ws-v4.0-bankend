<?php


namespace common\components;


use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftWastingTracking;
use yii\helpers\ArrayHelper;

class TrackingCode
{
    public static function UpdateTracking($result){

        $packing_code = ArrayHelper::getValue($result, 'packing_code');
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
        $product = \common\models\Product::findOne($soi);
        /** @var DraftDataTracking[] $finds */
        $finds = DraftDataTracking::find()->where([
            'tracking_code' => $tracking,
            'product_id' => $soi,
            'manifest_id' => $manifest_id,
            'manifest_code' => $manifest_code,
        ])->all();
        if ($finds) {
            echo "Có trong DraftDataTracking" . PHP_EOL;
            foreach ($finds as $find) {
                $find->status = DraftDataTracking::STATUS_CHECK_DETAIL;
                $find->number_get_detail = $find->number_get_detail ? $find->number_get_detail + 1 : 1;
                $find->save(0);
                $draft = new DraftBoxmeTracking();
                $draft->tracking_code = $tracking;
                $draft->manifest_code = $manifest_code;
                $draft->manifest_id = $manifest_id;
                $draft->quantity = $quantity;
                $draft->weight = $weight;
                $draft->product_id = $soi;
                $draft->order_id = $product? $product->order_id : null;
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
                ], [
                    'tracking_code' => $tracking,
                    'product_id' => $soi,
                    'manifest_id' => $manifest_id,
                    'manifest_code' => $manifest_code,
                ]);

                DraftMissingTracking::updateAll([
                    'status' => 'MERGED'
                ], [
                    'tracking_code' => $tracking,
                    'product_id' => $soi,
                    'manifest_id' => $manifest_id,
                    'manifest_code' => $manifest_code,
                ]);
            }
        } else {
            echo "Không có trong DraftDataTracking" . PHP_EOL;
            $wasting = new DraftWastingTracking();
            $wasting->tracking_code = $tracking;
            $wasting->manifest_code = $manifest_code;
            $wasting->manifest_id = $manifest_id;
            $wasting->product_id = $soi;
            $wasting->product_id = $product ? $product->order_id : null;
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
}