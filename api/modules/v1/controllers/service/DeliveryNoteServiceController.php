<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\helpers\WeshopHelper;
use common\models\DeliveryNote;
use common\models\Package;
use yii\helpers\ArrayHelper;

class DeliveryNoteServiceController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['merge'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'merge' => ['POST'],
        ];
    }
    public function actionMerge() {
        $listIds = \Yii::$app->request->post('ids');
        if(!$listIds){
            return $this->response(false,"Cannot see id!");
        }
        /** @var DeliveryNote[] $deliveryNotes */
        $deliveryNotes = DeliveryNote::find()->with(['packages'])->where(['id' => $listIds,'remove' => 0])->all();
        if(count($listIds) !== count($deliveryNotes)){
            return $this->response(false,"Some id is fail!");
        }
        $customer_id = null;
        $warehouse_id = null;
        $deliveryNoteNew = new DeliveryNote();
        $deliveryNoteNew->setAttributes($deliveryNotes[0]->getAttributes(),false);
        $deliveryNoteNew->id = null;
        $orderIds = [];
        $manifestCodes = [];
        foreach ($deliveryNotes as $deliveryNote){
            if($deliveryNote->shipment_id){
                return $this->response(false,"Delivery note ".$deliveryNote->delivery_note_code.' was been create shipment');
            }
            if($customer_id && $customer_id !== $deliveryNote->customer_id){
                return $this->response(false,"Delivery note need only customer");
            }
            $customer_id = $deliveryNote->customer_id;
            if($customer_id && $customer_id !== $deliveryNote->customer_id){
                return $this->response(false,"Delivery note need only customer");
            }
            if($warehouse_id && $warehouse_id != $deliveryNote->warehouse_id){
                return $this->response(false, 'You must choose the same warehouse!');
                break;
            }
            $warehouse_id = $deliveryNote->warehouse_id;
            foreach ($deliveryNote->packages as $package){
                if(!in_array($package->order_id,$orderIds)){
                    $orderIds[] = $package->order_id;
                }
                if(!in_array($package->manifest_code,$manifestCodes)){
                    $manifestCodes[] = $package->manifest_code;
                }
                if($package->insurance == 1){
                    $deliveryNoteNew->insurance = 1;
                }
                if($package->pack_wood == 1){
                    $deliveryNoteNew->pack_wood = 1;
                }
                if(!$deliveryNoteNew->tracking_reference_1){
                    $deliveryNoteNew->tracking_reference_1 = $package->tracking_code;
                }elseif (!$deliveryNoteNew->tracking_reference_2){
                    $deliveryNoteNew->tracking_reference_2 = $package->tracking_code;
                }else{
                    $deliveryNoteNew->tracking_reference_2 .= ',' . $package->tracking_code;
                }
                $deliveryNote->delivery_note_weight = $deliveryNote->delivery_note_weight ? $deliveryNote->delivery_note_weight + $package->weight : $package->weight;
            }
        }
        $deliveryNoteNew->order_ids = implode(',',$orderIds);
        $deliveryNoteNew->manifest_code = implode(',',$manifestCodes);
        $deliveryNoteNew->current_status = DeliveryNote::STATUS_REQUEST_SHIP_OUT;
        $deliveryNoteNew->remove = 0;
        $deliveryNoteNew->save(false);
        $deliveryNoteNew->delivery_note_code = WeshopHelper::generateTag($deliveryNoteNew->id,'WSVNDN');
        $deliveryNoteNew->save(false);
        Package::updateAll(
            ['delivery_note_code' => $deliveryNoteNew->delivery_note_code , 'delivery_note_id' => $deliveryNoteNew->id],
            ['delivery_note_id' => $listIds]
        );
        DeliveryNote::updateAll(
            ['remove' => 1],
            ['id' => $listIds]
        );
        return $this->response(true, 'Merge success!');
    }
}