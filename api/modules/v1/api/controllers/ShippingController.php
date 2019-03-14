<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:16
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\boxme\ShipInfoForm;
use common\models\db\Warehouse;
use common\models\Shipment;
use yii\helpers\ArrayHelper;

class ShippingController extends AuthController
{
    public function actionView($id,$action){
        $warehouse_id = ArrayHelper::getValue($this->post,'warehouse_id');
        if(!$warehouse_id){
            return ['mess' => "Không có warehouse_id"];
        }
//        if(!($form = System))
        /** @var Warehouse $warehouse */
        $warehouse = Warehouse::findOne($warehouse_id);
        $ship_form = new ShipInfoForm();
        $ship_form->phone = $warehouse->telephone;
        $ship_form->country = $warehouse->country_id == 1 ? "VN" : "ID";
        $ship_form->zipcode = $warehouse->post_code;
        $ship_form->province_id = $warehouse->post_code;
    }

    public function actionIndex($action){
        switch ($action){

        }
        $shipment = Shipment::find()
            ->with(['warehouseSend',''])
            ->where(['id']);


    }

    public function actionCreate($id){

    }
}