<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\helpers\WeshopHelper;
use common\models\DeliveryNote;
use common\models\Package;
use Yii;
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
        $create1vs1 = \Yii::$app->request->post('create1vs1');

        if((!$create1vs1 && !$infoDeliveryNote) || !$listPackage){
            return $this->response(false, 'Info delivery note or list package cannot null!');
        }
        if($infoDeliveryNote){
           return $this->create($listPackage,$infoDeliveryNote);
        }
        if($create1vs1){
            return $this->createMulti($listPackage);
        }
        return $this->response(false, 'Some thing error!');
    }
    function createMulti($listIds){
        $packages = Package::find()->with(['order'])->where(['id' => $listIds])->all();
        if(!$packages){
            return $this->response(false, 'Cannot find package !');
        }
        $count = 0;
        foreach ($packages as $package){
            if($package->delivery_note_code || $package->hold || $package->shipment_id ){
                continue;
            }
            if(!$package->order_id){
                continue;
            }
            $deliveryNote = new DeliveryNote();
            $deliveryNote->tracking_seller = $package->tracking_code;
            $deliveryNote->order_ids = $package->order_id;
            $deliveryNote->manifest_code = $package->manifest_code;
            $deliveryNote->delivery_note_weight = $package->weight;
            $deliveryNote->delivery_note_dimension_h = $package->dimension_h;
            $deliveryNote->delivery_note_dimension_l = $package->dimension_l;
            $deliveryNote->delivery_note_dimension_w = $package->dimension_w;
            if($package->dimension_h && $package->dimension_l && $package->dimension_w){
                $deliveryNote->delivery_note_change_weight = ($package->dimension_h * $package->dimension_w * $package->dimension_l)/5;
            }
            $deliveryNote->seller_shipped = $package->stock_in_us;
            $deliveryNote->stock_in_us = $package->stock_in_us;
            $deliveryNote->stock_out_us = $package->stock_out_us;
            $deliveryNote->stock_in_local = $package->stock_in_local;
            $deliveryNote->current_status = DeliveryNote::STATUS_REQUEST_SHIP_OUT;
            $deliveryNote->save(false);
            $deliveryNote->delivery_note_code = WeshopHelper::generateTag($deliveryNote->id,'WSVNDN');
            $deliveryNote->save(false);
            $package->delivery_note_code = $deliveryNote->delivery_note_code;
            $package->delivery_note_id = $deliveryNote->id;
            $package->save(0);
            $count ++;
        }
        return $this->response(true,'Create '.$count.'/'.count($listIds).' Delivery note success!');
    }
    function create($listPackage,$infoDeliveryNote){
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
        $packages = Package::find()->with(['order'])->where(['id' => $listPackage])->all();
        if(!$packages){
            return $this->response(false, 'Cannot find package !');
        }
        $customer_id = '';
        foreach ($packages as $package){
            if($package->delivery_note_code || $package->hold || $package->shipment_id ){
                return $this->response(false, 'Some package has been included in the delivery note!');
                break;
            }
            if(!$package->order_id){
                return $this->response(false, 'Have tracking unknown!');
                break;
            }
            if($customer_id && $customer_id != $package->order->customer_id){
                return $this->response(false, 'You must choose the same customer!');
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
    public function actionDelete($id){
        $model = Package::findOne($id);
        if(!$model){
            return $this->response(false, 'Cannot fill package!');
        }
        if($model->shipment_id){
            return $this->response(false, 'Package has shipment!');
        }
        $deliveryNote = DeliveryNote::findOne($model->delivery_note_id);
        if(!$deliveryNote){
            return $this->response(false, 'Cannot fill delivery note!');
        }
        if($deliveryNote->shipment_id){
            return $this->response(false, 'Delivery note has shipment!');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $packages = Package::find()->where(['<>','id',$model->id])->andWhere(['delivery_note_id' => $deliveryNote->id])->orderBy('id desc')->all();
            if($packages){
                $orderIds = [];
                $manifestCodes = [];
                foreach ($packages as $package){
                    if($package->delivery_note_code || $package->hold || $package->shipment_id ){
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
                $deliveryNote->tracking_seller = $deliveryNote->tracking_seller == $model->tracking_code ? $packages[0]->tracking_code : $deliveryNote->tracking_seller;
                $deliveryNote->tracking_reference_1 = $deliveryNote->tracking_reference_1 == $model->tracking_code ? $packages[0]->tracking_code : $deliveryNote->tracking_reference_1;
                $deliveryNote->tracking_reference_2 = $deliveryNote->tracking_reference_2 == $model->tracking_code ? $packages[0]->tracking_code : $deliveryNote->tracking_reference_2;
                $deliveryNote->order_ids = implode(',',$orderIds);
                $deliveryNote->manifest_code = implode(',',$manifestCodes);
                $deliveryNote->delivery_note_weight = $deliveryNote->delivery_note_weight - $model->weight > 0 ? $deliveryNote->delivery_note_weight - $model->weight : 0;;
                $deliveryNote->save(false);
            }
            $model->delivery_note_code = null;
            $model->delivery_note_id = null;
            $model->save(0);
            $transaction->commit();
            return $this->response(true, 'Remove success!');
        } catch (\Exception $exception) {
            Yii::error($exception, __METHOD__);
            $transaction->rollBack();
            return $this->response(true, 'Remove fail!');
        }
    }
}