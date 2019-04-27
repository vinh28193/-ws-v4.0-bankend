<?php


namespace common\boxme;


use common\helpers\UtilityHelper;
use common\models\draft\DraftBoxmeTracking;
use common\models\draft\DraftDataTracking;
use common\models\Package;
use common\models\draft\DraftWastingTracking;
use common\models\Manifest;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

class WarehouseInspect
{

    public static function update($row)
    {
        if (($packing_code = ArrayHelper::getValue($row, 'packing_code')) == null) {
            return [false, "offset column packing_code"];
        }
        if ($packing_code === 'RVSID-003') {
            $packing_code = 'RVSID-003-1002';
            $row['soi_tracking'] = null; // because RVSID-003 is invalid soi_tracking data, not use column soi_tracking
        } elseif ($packing_code === 'VAID001') {
            $packing_code = 'VAID001-1088';
        } elseif ($packing_code === 'TON2512') {
            $packing_code = 'TON2512-1132';
        } elseif ($packing_code === 'RVSID004') {
            $packing_code = 'RVSID004-1113';
        }
        $old = $packing_code;
        if (($packing_code = UtilityHelper::explodePackingCodePreg($packing_code)) === false) {
            return [false, "packing_code $old invalid format"];
        }
        $log['packing_code'] = $old;
        list($manifestCode, $manifestId) = $packing_code;

        /** $manifest  ShipmentManifests */
        if (($manifest = Manifest::findOne($manifestId)) === null) {
            return [false, "not found manifest $manifestCode with id $manifestId"];
        }

        $query = DraftDataTracking::find();
        $query->andWhere(['manifest_id' => $manifest]);

        if (($trackingCode = ArrayHelper::getValue($row, 'tracking_code')) == null) {
            return [false, "offset column tracking_code"];
        }

        if ($trackingCode !== null && UtilityHelper::isSubText($trackingCode, '*')) {
            $trackingCode = UtilityHelper::clearText($trackingCode, ['*']);
        }

        $query->andWhere([
            'OR',
            ['tracking_code' => $trackingCode],
            ['tracking_merge' => $trackingCode]
        ]);

        if (($wsTracking = ArrayHelper::getValue($row, 'soi_tracking')) == null) {
            return [false, "offset column tracking_code"];
        }

        if ($wsTracking !== null && UtilityHelper::isSubText($wsTracking, '*')) {
            $wsTracking = UtilityHelper::clearText($wsTracking, ['*']);
        }

        $query->andWhere(['ws_tracking_code' => $wsTracking]);

        // Update Value
        $attributes = [];
        if (isset($row['tag_code']) && ($tagCode = str_replace(['*'], '', $row['tag_code'])) !== null && UtilityHelper::isValidExcelValue($tagCode)) {
            $attributes['warehouse_tag_boxme'] = $tagCode;
        }
        if (isset($row['volume']) && ($volume = $row['volume']) !== null && UtilityHelper::isValidExcelValue($volume)) {
            $explodeVolume = $volume;
            if (strpos($explodeVolume, 'x') !== false) {
                $explodeVolume = StringHelper::explode($explodeVolume, 'x');
            } elseif (strpos($explodeVolume, '*') !== false) {
                $explodeVolume = StringHelper::explode($explodeVolume, '*');
            }
            if (is_array($explodeVolume) && count($explodeVolume) === 3) {
                list($width, $length, $high) = $explodeVolume;
                $attributes['dimension_w'] = $width;
                $attributes['dimension_l'] = $length;
                $attributes['dimension_h'] = $high;
            }
        }
        if (isset($row['item']) && ($item_name = $row['item']) !== null && UtilityHelper::isValidExcelValue($item_name)) {
            $attributes['item_name'] = $item_name;
        }
        if (isset($row['weight']) && ($shippingWeight = $row['weight']) !== null && UtilityHelper::isValidExcelValue($shippingWeight) && $shippingWeight > 0) {
            $attributes['weight'] = $shippingWeight;
        }
        if (isset($row['quantity']) && ($quantity = $row['quantity']) !== null && UtilityHelper::isValidExcelValue($quantity) && is_numeric($quantity) && $quantity > 0) {
            $attributes['quantity'] = $quantity;
        }
        if (isset($row['note']) && ($note = $row['note']) !== null && UtilityHelper::isValidExcelValue($note)) {
            $attributes['note_boxme'] = $note;
        }
        if (isset($row['status']) && ($status = $row['status']) !== null && UtilityHelper::isValidExcelValue($status)) {
            $attributes['status'] = $status;
        }
        if (isset($row['images']) && count($rawImages = ArrayHelper::getValue($row, 'images', [])) > 0) {
            $images = [];
            foreach ($rawImages as $image) {
                if (($url = ArrayHelper::getValue($image, 'urls')) !== null && UtilityHelper::isValidExcelValue($url)) {
                    $images[] = $url;
                }
            }
            $attributes['image'] = implode(';', $images);
        }

        $finds = $query->all();
        $msg = ["update callback"];
        if (UtilityHelper::isEmpty($finds)) {
            $attributes['tracking_code'] = $trackingCode;
            $attributes['manifest_code'] = $manifestCode;
            $attributes['manifest_id'] = $manifestId;
            $wastingQuery = DraftWastingTracking::find()->where([
                'AND',
                [
                    'OR',
                    ['tracking_code' => $trackingCode],
                    ['LIKE', 'tracking_merge', $trackingCode]
                ],
                ['manifest_id' => $manifestId],
//                ['ws_tracking_code' => $wsTracking]

            ]);
            if (($wasting = $wastingQuery->one()) === null) {
                $wasting = new DraftWastingTracking();
            }
            if (isset($attributes['ws_tracking_code'])) {
                unset($attributes['ws_tracking_code']);
            }
            $wasting->setAttributes($attributes, false);
            $wasting->save(false);
            $msg[] = "created new wasting {$wasting->id}";
        } else {
            foreach ($finds as $find) {
                /* @var $find DraftDataTracking */
                if ($find->status === DraftBoxmeTracking::STATUS_CALLBACK_SUCCESS) {
                    $draftQuery = Package::find();
                    $draftQuery->where([
                        'AND',
                        ['tracking_code' => $find->tracking_code],
                        ['manifest_id' => $find->manifest_id],
                        ['ws_tracking_code' => $find->ws_tracking_code]
                    ]);
                    if (($draft = $draftQuery->one()) !== null) {
                        $msg[] = "update for package {$draft->id}";
                        $draft->updateAttributes($attributes);
                        $log['attributes'][$draft->id] = $attributes;
                        continue;
                    }
                }
                $attributes['purchase_invoice_number'] = $find->purchase_invoice_number;
                $attributes['draft_data_tracking_id'] = $find->id;
                $attributes['tracking_code'] = $find->tracking_code;
                $attributes['manifest_id'] = $find->manifest_id;
                $attributes['ws_tracking_code'] = $find->ws_tracking_code;
                $attributes['purchase_invoice_number'] = $find->purchase_invoice_number;
                $attributes['seller_refund_amount'] = $find->seller_refund_amount;
                $attributes['type_tracking'] = $find->type_tracking;
                $attributes['tracking_merge'] = strtolower($find->tracking_code) == strtolower($trackingCode) ? $find->tracking_merge : strtolower($trackingCode) . ',' . $find->tracking_merge;
                $attributes['price'] = $find->product ? $find->product->price_amount_local : 0;
                $attributes['product_id'] = $find->product_id;
                $attributes['order_id'] = $find->order_id;
                $draft = new Package($attributes);
                $draft->createOrUpdate(false);
                $msg[] = "create new package {$draft->id}";
                $log['attributes'][$draft->id] = $attributes;
                DraftWastingTracking::updateAll([
                    'status' => DraftWastingTracking::MERGE_CALLBACK
                ], [
                    'tracking_code' => $trackingCode,
//                    'ws_tracking_code' => $wsTracking,
                    'manifest_id' => $manifestId,
                    'manifest_code' => $manifestCode,
                ]);
                $find->status = DraftBoxmeTracking::STATUS_CALLBACK_SUCCESS;
                if (isset($attributes['item_name'])) {
                    $find->item_name = $attributes['item_name'];
                }
                $find->save(false);
            }
        }
        return [true, implode(', ', $msg)];
    }
}