<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 17:24
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;

class CartController extends BaseApiController
{

    public function actionAddToCart(){
        return $this->response(true,"ok",$this->getCart());
    }

    protected function getCart(){
        return Yii::$app->cart;
    }
}