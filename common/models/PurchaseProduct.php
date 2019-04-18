<?php


namespace common\models;
use common\models\draft\DraftExtensionTrackingMap;

/**
 * Class PurchaseProduct
 * @package common\models
 * @property PurchaseOrder $purchaseOrder
 */

class PurchaseProduct extends \common\models\db\PurchaseProduct
{
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(),['id' => 'purchase_order_id']);
    }
}