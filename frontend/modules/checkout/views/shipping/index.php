<?php

use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\payment\Payment $payment */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */


$showStep = true;
$activeStep = 2;
?>
<!--<div id="step_checkout_1" style="display: --><? //= $activeStep === 1 ? 'block' : 'none'; ?><!--">-->
<!--    --><?php //echo $this->render('step/step1', ['activeStep' => $activeStep]); ?>
<!--</div>-->
<div style="display: <?= $activeStep === 1 ? 'none' : 'block' ?>">
    <?= $this->render('step/step2', [
        'activeStep' => $activeStep,
        'shippingForm' => $shippingForm,
        'provinces' => $provinces,
        'payment' => $payment,
    ]); ?>
</div>