<?php


namespace frontend\modules\checkout\controllers;

use common\components\cart\CartSelection;
use Yii;
use common\components\cart\CartHelper;
use frontend\modules\checkout\models\ShippingForm;
use frontend\modules\checkout\Payment;
use common\models\SystemStateProvince;
use yii\helpers\ArrayHelper;

class ShippingController extends CheckoutController
{

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'sub-district' => [
                'class' => 'common\actions\DepDropAction',
                'defaultSelect' => true,
                'useAction' => 'common\models\SystemDistrict::select2Data'
            ]
        ]);
    }

    public function actionIndex()
    {
        $activeStep = 2;
        if (Yii::$app->user->isGuest) {
            $activeStep = 1;
        }
        CartSelection::setSelectedItems(CartSelection::TYPE_BUY_NOW,[
            '7cb2d4d3bbec4374c21e8843a48154a6',
            '68f552c8422eb643d27858c4f83be0e1'
        ]);

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
        $shippingForm->setDefaultValues();
        $provinces = SystemStateProvince::select2Data(1);
        return $this->render('index', [
            'activeStep' => $activeStep,
            'shippingForm' => $shippingForm,
            'payment' => $payment,
            'provinces' => $provinces
        ]);
    }

}