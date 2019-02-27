<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/02/2019
 * Time: 14:42
 */

namespace api\modules\v1\api\controllers;


use api\modules\v1\controllers\AuthController;

class ShipmentController extends AuthController
{
    public function actionList(){
        print_r($request_post);
        die;
    }
}