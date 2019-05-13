<?php


namespace frontend\modules\checkout\controllers;

use Yii;
use common\components\cart\CartHelper;
use frontend\modules\checkout\models\ShippingForm;
use frontend\modules\checkout\Payment;
use frontend\modules\checkout\PaymentService;

class ShippingController extends CheckoutController
{

    public function actionIndex()
    {
        $activeStep = 2;
        if(Yii::$app->user->isGuest){
            $activeStep = 1;
        }
        $items = $this->module->cartManager->getItems();
        if (empty($items)) {
            return $this->render('empty_cart');
        }
        $params = CartHelper::createOrderParams($items);
        $payment = new Payment([
            'page' => Payment::PAGE_CHECKOUT,
            'orders' => $params['orders'],
            'total_amount' => $params['totalAmount'],
            'total_amount_display' => $params['totalAmount']
        ]);
        $shippingForm = new ShippingForm();
        return $this->render('index',[
            'activeStep' => $activeStep,
            'shippingForm' => $shippingForm,
            'payment' => $payment,
        ]);
    }

}