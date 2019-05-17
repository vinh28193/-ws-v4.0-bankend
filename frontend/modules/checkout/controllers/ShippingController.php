<?php


namespace frontend\modules\checkout\controllers;


use Yii;
use common\components\cart\CartHelper;
use common\payment\models\ShippingForm;
use common\payment\Payment;
use common\models\SystemStateProvince;
use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;

class ShippingController extends CheckoutController
{

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

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

    public function actionIndex($type)
    {
        $activeStep = 2;
        $isGuest = Yii::$app->user->isGuest;
        if ($isGuest) {
            $activeStep = 1;
        }
        if (($keys = CartSelection::getSelectedItems($type)) === null) {
            return $this->goBack();
        }
        $payment = new Payment([
            'page' => Payment::PAGE_CHECKOUT,
            'carts' => $keys,
            'payment_type' => $type,
        ]);
        $payment->initDefaultMethod();
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