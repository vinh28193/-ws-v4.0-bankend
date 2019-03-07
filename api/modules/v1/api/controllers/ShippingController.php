<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:16
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\Shipment;

class ShippingController extends AuthController
{
    public function actionView($id,$action){
        $shipment = Shipment::find()
            ->with(['warehouseSend',''])
            ->where(['']);


    }

    public function actionCreate($id){

    }
}