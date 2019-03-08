<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/02/2019
 * Time: 14:42
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\PackageItem;
use common\models\Shipment;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ShipmentController extends AuthController
{
    public function actionIndex()
    {
        $limit = ArrayHelper::getValue($this->get, 'limit', 20);
        $page = ArrayHelper::getValue($this->get, 'page', 1);
        $model = Shipment::find()->where(['active' => 1])->limit($limit)->offset($page - 1 * $limit)->asArray()->all();
        return $model;
    }

    public function actionUpdate($id)
    {
        $model = Shipment::findOne($id);
        if ($model) {
            $model->setAttributes($this->post);
            $model->save();
        }
        return $model->toArray();
    }

    public function actionView($id)
    {
        $model = Shipment::find()
            ->where(['id' => $id,])
            ->with([
                'packageItems' => function ($qr) {
                    /** @var ActiveQuery $qr */
                    $qr->with([
                        'order' => function ($qr) {
                            /** @var ActiveQuery $qr */
                            $qr->where(['remove' => 0]);
                        },
                    ])->where(['remove' => 0]);
                },
            ])->asArray()->limit(1)->one();
        return $model;
    }

    public function actionDelete($id,$is_leave = false)
    {
        $countShipment = Shipment::updateAll(['shipment_status' => Shipment::STATUS_REMOVE_SHIPMENT,'updated_at' => time()],['id' => $id]);
        $countLeave = 0;
        if($is_leave == 'leave'){
            $countLeave = PackageItem::updateAll(['shipment_id' => '','updated_at' => time()],['shipment_id' => $id]);
        }
        return ['message' => 'Remove success','total' => $countShipment,'total_shipment_leave' => $countLeave];
    }

    public function actionCreate()
    {
        $packageItem_ids = ArrayHelper::getValue($this->post, 'packageItemIds', null);
        if(!$packageItem_ids){
            return [];
        }
        $model = new Shipment();
        $model->setAttributes($this->post);
        if($model->validate()){
            $packageItem_ids = explode(',',$packageItem_ids);
            /** @var PackageItem[] $list_IPackage */
            $list_IPackage = PackageItem::find()->where(['id' => $packageItem_ids])
                ->andWhere(['or',['shipment_id' => null],['shipment_id' => '']])
                ->all();
            $list_cbTag = [];
            $list_id_success = [];
            $model->total_weight = 0;
            $model->total_quantity = 0;
            $model->total_cod = 0;
            $model->total_price = 0;
            $checkInspect = true;
            foreach ($list_IPackage as $value){
                if(!$value->box_me_warehouse_tag)
                    $checkInspect = false;
                if ($value->box_me_warehouse_tag){
                    $list_cbTag[] = $value->box_me_warehouse_tag;
                }
                $model->total_weight += $value->weight ? $value->weight : 0;
                $model->total_quantity += $value->quantity ?  $value->quantity : 0;
                $model->total_price += $value->price ? $value->price* $value->quantity : 0;
                $model->total_cod += $value->cod ? $value->cod : 0;
                $list_id_success[] = $value->id;
            }
            $model->shipment_status = Shipment::STATUS_NEW;
            if($checkInspect){
                $model->shipment_status = Shipment::STATUS_LOCAL_INSPECT_DONE;
            }
            $model->warehouse_tags = implode(',',$list_cbTag);
            $model->save();
            $rows = PackageItem::updateAll(['shipment_id' => $model->id] , ['id' => $list_id_success]);
            return ['total_item' => $rows,'data' => $model->toArray()];
        }

        return $model->errors;
    }
}