<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
use common\models\db\DraftExtensionTrackingMap;
use common\models\db\PurchaseOrder;
use common\models\db\PurchaseProduct;
use common\models\TrackingCode;

class ExtensionController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'update' => ['POST'],
        ];
    }
    public function actionUpdate(){
        $tranId = \Yii::$app->request->post("tran_id");
        $trackingCode = \Yii::$app->request->post("tracking_code");
        $sku = \Yii::$app->request->post("sku");
        $estimate = \Yii::$app->request->post("estimate");
        $quantity = \Yii::$app->request->post("quantity");
        $status = \Yii::$app->request->post("status");

        /** @var PurchaseOrder $purchaseOrder */
        $purchaseOrder = PurchaseOrder::find()->where(['purchase_order_number' => $tranId])->one();
        if(!$purchaseOrder){
            return $this->response(false,'can not find tranid '.$tranId.' in data!');
        }
        /** @var PurchaseProduct[] $purchaseProducts */
        $purchaseProducts = PurchaseProduct::find()->where(['sku' => $sku,'purchase_order_id' => $purchaseOrder->id])->with(['product'])->all();
        $total = 0;
        foreach ($purchaseProducts as $purchaseProduct){
            $qty = $quantity - $total;
            $qty = $qty > 0 ? ($purchaseProduct->purchase_quantity > $qty ? $qty : $purchaseProduct->purchase_quantity) : 0;
            $total += $qty;
            $ext = DraftExtensionTrackingMap::find()->where([
                'tracking_code' => $trackingCode,
                'product_id' => $purchaseProduct->product_id,
                'order_id' => $purchaseProduct->order_id,
                'purchase_invoice_number' => $purchaseOrder->purchase_order_number
            ])->one();
            if(!$ext){
                $ext = new DraftExtensionTrackingMap();
                $ext->tracking_code = $trackingCode;
                $ext->product_id = $purchaseProduct->product_id;
                $ext->order_id = $purchaseProduct->order_id;
                $ext->purchase_invoice_number = $purchaseOrder->purchase_order_number;
            }
            $ext->status = $status;
            $ext->quantity = $qty;
            $ext->number_run = $ext->number_run ? $ext->number_run + 1 : 1;
            $ext->save();
            $draft_data = DraftDataTracking::find()->where([
                'tracking_code' => $trackingCode,
                'product_id' => $purchaseProduct->product_id,
                'order_id' => $purchaseProduct->order_id,
            ])->one();
            if(!$draft_data){
                $draft_data = DraftDataTracking::find()->where([
                    'tracking_code' => $trackingCode,
                    'product_id' => null,
                    'order_id' => null,
                ])->one();
                if(!$draft_data){
                    $draft_data = new DraftDataTracking();
                }
                $draft_data->tracking_code = $trackingCode;
                $draft_data->product_id = $purchaseProduct->product_id;
                $draft_data->order_id = $purchaseProduct->order_id;
                $draft_data->tracking_code = $trackingCode;
            }
        }
        return $this->response(true,'Update success!');
    }
}