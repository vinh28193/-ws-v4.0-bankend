<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 14:07
 */

namespace backend\controllers;

use common\models\Order;
use Yii;

class DevModeController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if (YII_ENV !== 'dev') {
            return false;
        }
        return parent::beforeAction($action);
    }


    public function actionIndex()
    {

        $fees = [
            'origin_fee' => 60,
            'origin_tax_fee' => 634.9,
            'origin_shipping_fee' => 13,
        ];
//        $order = Order::find()->one();
//        var_dump($order);die;
        $order = new Order();
        $order->total_weight = 12;
        $order->exchange_rate_fee = 2333;
        $order->setAdditionalFees($fees, true, true);
        foreach (array_keys($fees) as $name) {
            $owner = "total_{$name}_local";
            if ($order->hasAttribute($name)) {
                $value = $order->$owner;
                var_dump("$owner : $value \n");
            }
        }
        var_dump($order->getAdditionalFees());
        die;
    }

    public function actionCart()
    {

        /** @var  $cart \common\components\cart\CartManager */

        $cart = Yii::$app->cart;
        //$cart->removeItems();
        $cart->addItem('BBBBB11111AA', 'sadasda', 1, 'ebay', ['sdasd'], '1222');
        $cart->addItem('9999999999', 'vinh', 1, 'ebay', ['1223'], null);
        var_dump($cart->getItems());
        $cart->removeItem('9999999999');
        var_dump('brrrr');
        var_dump($cart->countItems());
        die;


    }
}