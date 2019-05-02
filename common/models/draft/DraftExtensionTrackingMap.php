<?php


namespace common\models\draft;
use common\models\db\PurchaseOrder;
use common\models\Order;
use common\models\Product;

/**
 * Class DraftExtensionTrackingMap
 * @package common\models\draft
 * @property Order $order
 * @property Product $product
 * @property PurchaseOrder $purchase
 */

class DraftExtensionTrackingMap extends \common\models\db\DraftExtensionTrackingMap
{
    const JOB_CHECKED = "JOB_CHECKED";
    const MAPPED = "MAPPED";
    const US_RECEIVED = "US_RECEIVED";
    const STATUST_NEW = "NEW";

    public function getOrder(){
        return $this->hasOne(Order::className(),['id' => 'order_id']);
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['id' => 'product_id']);
    }

    public function getPurchase(){
        return $this->hasOne(PurchaseOrder::className(),['purchase_order_number' => 'purchase_invoice_number']);
    }
}