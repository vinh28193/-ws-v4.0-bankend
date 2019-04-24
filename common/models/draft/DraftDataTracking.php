<?php
namespace common\models\draft;
use common\models\Manifest;
use common\models\Order;
use common\models\Product;
use common\models\PurchaseOrder;
use common\models\TrackingCode;

/**
 * Class DraftDataTracking
 * @package common\models\draft
 * @property DraftExtensionTrackingMap $draftExtensionTrackingMap
 * @property TrackingCode $trackingCode
 * @property DraftBoxmeTracking $draftBoxmeTracking
 * @property DraftMissingTracking $draftMissingTracking
 * @property DraftWastingTracking $draftWastingTracking
 * @property DraftPackageItem $draftPackageItem
 * @property Manifest $manifest
 * @property Product $product
 * @property Order $order
 * @property PurchaseOrder $purchaseOrder
 */
class DraftDataTracking extends \common\models\db\DraftDataTracking
{
    const STATUS_EXTENSION = "EXTENSION";
    const STATUS_MAKE_US_SENDING = "MAKE_US_SENDING";
    const STATUS_CHECK_DONE = "CHECK_DONE";
    const STATUS_CHECK_DETAIL = "CHECK_DETAIL";
    const STATUS_CALLBACK_SUCCESS = "CALLBACK";
    const STATUS_MERGE_WAST = "MERGE_WAST";
    const TYPE_NORMAL = "NORMAL";
    const TYPE_SPLIT = "SPLIT";
    const TYPE_UNKNOWN = "UNKNOWN";

    public function createOrUpdate($validate = true){
        $draft_data = self::find()->where([
            'tracking_code' => $this->tracking_code,
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
        ])->one();
        if(!$draft_data){
            $draft_data = self::find()->where([
                'tracking_code' => $this->tracking_code,
                'product_id' => null,
                'order_id' => null,
            ])->one();
            if(!$draft_data){
                $draft_data = new self();
            }
        }
        $status = $draft_data->status != null && $draft_data->status != $this->status ? self::STATUS_CHECK_DONE : $this->status;
        $draft_data->setAttributes($this->getAttributes());
        $draft_data->status = $status;
        return $draft_data->save($validate);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftDataTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftDataTrackingQuery(get_called_class());
    }

    public function getDraftExtensionTrackingMap(){
        return $this->hasOne(DraftExtensionTrackingMap::className(),['tracking_code' => 'tracking_code','product_id' => 'product_id']);
//        return DraftExtensionTrackingMap::find()->where(['tracking_code' => $this->tracking_code,'product_id' => $this->product_id])->one();
    }
    public function getTrackingCode(){
        return $this->hasOne(TrackingCode::className(),['tracking_code' => 'tracking_code']);
    }

    public function getDraftBoxmeTracking(){
        return $this->hasOne(DraftBoxmeTracking::className(),['tracking_code' => 'tracking_code','product_id' => 'product_id']);
//        return DraftBoxmeTracking::find()->where(['tracking_code' => $this->tracking_code,'product_id' => $this->product_id])->one();
    }

    public function getDraftMissingTracking(){
        return $this->hasOne(DraftMissingTracking::className(),['tracking_code' => 'tracking_code','product_id' => 'product_id']);
//        return DraftMissingTracking::find()->where(['tracking_code' => $this->tracking_code,'product_id' => $this->product_id])->one();
    }

    public function getDraftWastingTracking(){
        return $this->hasOne(DraftWastingTracking::className(),['tracking_code' => 'tracking_code','product_id' => 'product_id']);
//        return DraftWastingTracking::find()->where(['tracking_code' => $this->tracking_code,'product_id' => $this->product_id])->one();
    }

    public function getDraftPackageItem(){
        return $this->hasOne(DraftPackageItem::className(),['tracking_code' => 'tracking_code','product_id' => 'product_id']);
//        return DraftPackageItem::find()->where(['tracking_code' => $this->tracking_code,'product_id' => $this->product_id])->one();
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getManifest(){
        return $this->hasOne(Manifest::className(), ['id' => 'manifest_id']);
    }

    public function getPurchaseOrder(){
        return $this->hasOne(PurchaseOrder::className(), ['purchase_order_number' => 'purchase_invoice_number']);
    }
}