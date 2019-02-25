<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 14:07
 */

namespace backend\controllers;


use common\models\Order;

class DevModeController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if(YII_ENV !== 'dev'){
            return false;
        }
        return parent::beforeAction($action);
    }


    public function actionIndex(){

        $fees = [
            'origin_fee' => 60,
            'origin_tax_fee' => 1.23,
            'origin_shipping_fee' => 5
        ];

        $order = new Order();
        $order->total_weight = 12;
        $order->setAdditionalFees($fees,true);
        foreach (array_keys($fees) as $name){
            $owner = "total_{$name}_local";
            if($order->hasAttribute($name)){
                $value = $order->$owner;
                var_dump("$owner : $value \n");
            }
        }
        var_dump($order->getAdditionalFees());
        die;
    }
}