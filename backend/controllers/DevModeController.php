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
            'price_amount' => 600, //US
            'tax_us_amount' => 5,  //US
            'shipping_us_amount' => 10,  //US
            'intl_shipping_fee_amount' => 9999999, //US phí này không được set
            'custom_fee_amount' => 10, //US phí này không được set
            'delivery_fee_amount' => 123, //US phí này không được set
        ];

        $order = new Order();
        $order->setAdditionalFees($fees,true);
        foreach (array_keys($fees) as $name){
            $owner = "total_{$name}_local";
            if($order->canGetProperty($name)){
                $value = $order->$owner;
                var_dump("$owner : $value \n");
            }
        }
        $order->save(false);
        var_dump($order);
        die;
    }
}