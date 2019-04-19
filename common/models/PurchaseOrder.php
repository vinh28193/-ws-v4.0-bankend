<?php


namespace common\models;
use common\models\draft\DraftExtensionTrackingMap;

/**
 * Class PurchaseOrder
 * @package common\models
 * @property DraftExtensionTrackingMap $draftExtensionTrackingMap
 */

class PurchaseOrder extends \common\models\db\PurchaseOrder
{
    public function getDraftExtensionTrackingMap(){
        return $this->hasMany(DraftExtensionTrackingMap::className(),['purchase_invoice_number' => 'purchase_order_number']);
    }
}