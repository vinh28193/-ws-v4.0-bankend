<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\helpers\WeshopHelper;
use common\models\DeliveryNote;
use common\models\Package;
use yii\helpers\ArrayHelper;

class DeliveryNoteController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['operation','master_operation', 'tester', 'master_sale', 'sale']
            ],
        ];
    }
    public function actionIndex(){

    }
    public function actionCreate(){
        $listPackage = \Yii::$app->request->post('listPackage');
        $infoDeliveryNote = \Yii::$app->request->post('infoDeliveryNote');
        if(!$infoDeliveryNote || !$listPackage){
            return $this->response(false, 'Info delivery note or list package cannot null!');
        }
        $weight = ArrayHelper::getValue($infoDeliveryNote,'weight',0);
        $length = ArrayHelper::getValue($infoDeliveryNote,'length',0);
        $width = ArrayHelper::getValue($infoDeliveryNote,'width',0);
        $height = ArrayHelper::getValue($infoDeliveryNote,'height',0);
        if(!$weight){
            return $this->response(false, 'Weight must be greater than 0 !');
        }
        $orderIds = [];
        $manifestCodes = [];
        /** @var Package[] $packages */
        $packages = Package::find()->where(['id' => $listPackage])->all();
        if(!$packages){
            return $this->response(false, 'Cannot find package !');
        }
        foreach ($packages as $package){
            if($package->delivery_note_code){
                return $this->response(false, 'Some package has been included in the delivery note!');
                break;
            }
            if(!$package->order_id){
                return $this->response(false, 'Have tracking unknown!');
                break;
            }
            $orderIds = array_merge([$package->order_id],$orderIds);
            $manifestCodes = array_merge([$package->manifest_code],$manifestCodes);
        }
        $weightChange = 0;
        if($height && $width && $length){
            $weightChange = ($height * $width * $length)/5; // cm => gram
        }
        $deliveryNote = new DeliveryNote();
        $deliveryNote->tracking_seller = $packages[0]->tracking_code;
        $deliveryNote->tracking_reference_1 = isset($packages[1]) && $packages[1] ? $packages[1]->tracking_code : null;
        $deliveryNote->tracking_reference_2 = isset($packages[2]) && $packages[2] ? $packages[2]->tracking_code : null;
        $deliveryNote->order_ids = implode(',',$orderIds);
        $deliveryNote->manifest_code = implode(',',$manifestCodes);
        $deliveryNote->delivery_note_weight = $weight;
        $deliveryNote->delivery_note_dimension_h = $height;
        $deliveryNote->delivery_note_dimension_l = $length;
        $deliveryNote->delivery_note_dimension_w = $width;
        $deliveryNote->delivery_note_change_weight = $weightChange;
        $deliveryNote->seller_shipped = $packages[0]->stock_in_us;
        $deliveryNote->stock_in_us = $packages[0]->stock_in_us;
        $deliveryNote->stock_out_us = $packages[0]->stock_out_us;
        $deliveryNote->stock_in_local = $packages[0]->stock_in_local;
        $deliveryNote->current_status = DeliveryNote::STATUS_REQUEST_SHIP_OUT;
        $deliveryNote->save(false);
        $deliveryNote->delivery_note_code = WeshopHelper::generateTag($deliveryNote->id,'WSVNDN');
        $deliveryNote->save(false);
        Package::updateAll(
            ['delivery_note_code' => $deliveryNote->delivery_note_code , 'delivery_note_id' => $deliveryNote->id],
            ['id' => $listPackage]
        );
        return $this->response(true, 'Create Delivery success!');
    }
}