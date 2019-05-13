<?php

use yii\helpers\Html;
use frontend\modules\checkout\WizardWidget;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\checkout\Payment $payment */
/* @var frontend\modules\checkout\models\ShippingForm $shippingForm */
/* @var array $provinces */

$showStep = true;

//echo $this->render('step/step1', ['activeStep' => $activeStep]);
echo $this->render('step/step2',[
    'activeStep' => $activeStep,
    'shippingForm' => $shippingForm,
    'provinces' => $provinces,
]);
