<?php


namespace frontend\modules\cart\controllers;


use Yii;

class AddToCartController extends CartController
{


    public function actionIndex(){
        Yii::info(Yii::$app->request->post(),'POST');
        if (($key = $this->module->cartManager->addItem(Yii::$app->request->post())) === false) {
            return ['success' => false,'message' => 'Can not Buy now this item'];
        };
        return  ['success' => true,'message' => 'Can not Buy now this item', 'data' => $key];
    }
}