<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 23/02/2019
 * Time: 11:10
 */

namespace backend\modules\v1\weshop\customer\controllers;


use backend\modules\v1\weshop\controllers\BaseAuthorController;

class OrderController extends BaseAuthorController
{
    public function actionIndex(){
        print_r($this->request);
//        print_r(\Yii::$app->user->getIdentity());
        die;
    }
}