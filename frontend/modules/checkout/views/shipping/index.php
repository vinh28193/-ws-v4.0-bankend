<?php

use yii\helpers\Html;
use frontend\modules\checkout\WizardWidget;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var common\payment\Payment $payment */
/* @var common\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */

$showStep = true;
if($activeStep === 1){

}
//echo $this->render('step/step1', ['activeStep' => $activeStep]);
echo $this->render('step/step2',[
    'activeStep' => $activeStep,
    'shippingForm' => $shippingForm,
    'provinces' => $provinces,
    'payment' => $payment,
]);
//echo $this->render('step/step3',[
//    'activeStep' => $activeStep,
//    'shippingForm' => $shippingForm,
//    'payment' => $payment,
//]);