<?php

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var common\payment\Payment $payment */
/* @var common\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */

$showStep = true;
if($activeStep === 1){

}
//echo $this->render('step/step1', ['activeStep' => $activeStep]);
?>
<div id="step_checkout_1" style="display: none">
    <?= $this->render('step/step1', ['activeStep' => $activeStep]); ?>
</div>
<div id="step_checkout_2" style="display: block">
    <?= $this->render('step/step2', [
        'activeStep' => $activeStep,
        'shippingForm' => $shippingForm,
        'provinces' => $provinces,
        'payment' => $payment,
    ]); ?>
</div>
<div id="step_checkout_3" style="display: none">
    <?= $this->render('step/step3', [
        'activeStep' => $activeStep,
        'shippingForm' => $shippingForm,
        'payment' => $payment,
    ]); ?>
</div>