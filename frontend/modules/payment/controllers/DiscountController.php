<?php


namespace frontend\modules\payment\controllers;

use frontend\modules\payment\models\Order;
use Yii;
use yii\helpers\ArrayHelper;
use frontend\modules\payment\Payment;

class DiscountController extends BasePaymentController
{

    public function actionCheckPromotion()
    {

        $bodyParams = $this->request->bodyParams;
        $payment = new Payment($bodyParams);
        $orderParams = ArrayHelper::remove($bodyParams, 'orders', []);
        if (empty($orderParams)) {
            return $this->response(false, 'empty');
        }
        $orders = [];
        foreach ($orderParams as $orderParam) {
            if (isset($orderParam['totalFinalAmount'])) {
                unset($orderParam['totalFinalAmount']);
            }
            if (isset($orderParam['totalAmountLocal'])) {
                unset($orderParam['totalAmountLocal']);
            }
            $order = new Order($orderParam);
            if ($order->cartId !== null && $order->createOrderFromCart() !== false) {
                $orders[$order->cartId] = $order;
            } else if ($order->ordercode !== null && ($order = Order::findOne(['ordercode' => $order->ordercode])) !== null) {
                $orders[$order->ordercode] = $order;
            }
        }
        $payment->setOrders($orders);
        $response = $payment->checkPromotion();
        return $this->response(true, 'check sucess', $response);
    }
}