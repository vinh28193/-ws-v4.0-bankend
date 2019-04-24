<?php


namespace common\models\draft;


use common\models\Manifest;
use common\models\Order;
use common\models\Product;
use common\models\PurchaseOrder;

/**
 * Class DraftMissingTracking
 * @package common\models\draft
 * @property Manifest $manifest
 * @property Product $product
 * @property Order $order
 * @property PurchaseOrder $purchaseOrder
 */
class DraftMissingTracking extends \common\models\db\DraftMissingTracking
{
    const MERGE_CALLBACK = 'MERGE_CALLBACK';
    const SPILT_MANUAL = 'SPILT_MANUAL';
    const MERGE_MANUAL = 'MERGE_MANUAL';
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftMissingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftMissingTrackingQuery(get_called_class());
    }

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
        $draft_data->setAttributes($this->getAttributes());
        return $draft_data->save($validate);
    }
    public function getOrder()
    {
        return $this->hasOne(\common\models\Order::className(), ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(\common\models\Product::className(), ['id' => 'product_id']);
    }

    public function getManifest(){
        return $this->hasOne(\common\models\Manifest::className(), ['id' => 'manifest_id']);
    }

    public function getPurchaseOrder(){
        return $this->hasOne(PurchaseOrder::className(), ['purchase_order_number' => 'purchase_invoice_number']);
    }
}