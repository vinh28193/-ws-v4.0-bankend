<?php

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\payment\Payment $payment */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */

/** GA CHECKOUT **/
$js_ga = <<<JS
$(document).ready(function() {
    var client = new ClientJS();
            var _fingerprint = client.getFingerprint();
            var data = {
                fingerprint: _fingerprint,
                path : window.location.pathname
            };
    // Ga Checkout
  setTimeout(function () { 
        ws.ajax('/frontend/u',{
        type: 'POST',
        dataType: 'json',
        data: data,
        loading: true,
        success: function (result) {
            //console.log(result);  console.log(result.success); 
        }
        });
    }, 1000 * 1);
  
   setTimeout(function () { 
        ws.ajax('/checkout/shipping/u',{
        type: 'POST',
        dataType: 'json',
        data: data,
        loading: true,
        success: function (result) {
            console.log(result);  console.log(result.success); 
        }
        });
    }, 1000 * 1);
});
JS;
$this->registerJs($js_ga, \yii\web\View::POS_END);

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
