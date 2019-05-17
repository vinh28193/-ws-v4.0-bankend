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
if(Yii::$app->user->isGuest){?>
    <div id="step_checkout_1" style="display: block">
        <?= $this->render('step/step1', ['activeStep' => $activeStep]); ?>
    </div>
<?php }else{ ?>
    <div style="display: <?= Yii::$app->user->isGuest ? 'none' : 'block' ?>">
        <?= $this->render('step/step2', [
            'activeStep' => $activeStep,
            'shippingForm' => $shippingForm,
            'provinces' => $provinces,
            'payment' => $payment,
        ]); ?>
    </div>
<?php } ?>