<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\View;

/* @var yii\web\View $this */
/* @var boolean $statusOtp */
/* @var boolean $isValid */
/* @var string $msg */
/* @var frontend\modules\payment\models\OtpVerifyForm $otpVerifyForm */
/* @var array $transactionDetail */
/* @var array $walletInterview */
/* @var string $redirectUri */

$js = <<<JS
    ws.initEventHandler($('form#otpVerifyForm','submit','beforeSubmit','form#otpVerifyForm',function(e) {
         e.preventDefault();
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) 
        {
            return false;
        }
        var data = form.serialize();
        console.log(data);
        // send data to actionSave by ajax request.
        return false; // Cancel form submitting.
    }));
JS;
$this->registerJs($js);
if (!$statusOtp) {
    if (!$isValid && $transactionDetail['type'] === 'PAY_ORDER' && $transactionDetail['status'] === 4) {
        echo Html::beginTag('div', ['class' => 'button_group']);
        echo Html::a('Top Up', $redirectUri, ['class' => 'btn btn-success']);
        echo Html::a('Go Back', $redirectUri, ['class' => 'btn btn-default']);
        echo Html::endTag('div');
    } else if ($msg !== null) {
        echo Html::tag('p', $msg);
    }
} else {
    echo Html::tag('div', 'Xác thực OTP', ['class' => 'modal-title']);
    if ($msg !== null) {
        echo Html::tag('p', $msg);
    }
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'otpVerifyForm'
        ],
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => '/payment/wallet/otp-verify-form-validate',
    ]);
    echo $form->field($otpVerifyForm, 'otpReceive')->hiddenInput()->label(false);
    echo $form->field($otpVerifyForm, 'transactionCode')->hiddenInput()->label(false);
    echo $form->field($otpVerifyForm, 'returnUrl')->hiddenInput()->label(false);
    echo $form->field($otpVerifyForm, 'cancelUrl')->hiddenInput()->label(false);
    echo $form->field($otpVerifyForm, 'optCode')->textInput();
    echo $form->field($otpVerifyForm, 'captcha')->widget(Captcha::className(), [
        'captchaAction' => '/otp/captcha',
        'options' => ['class' => 'form-control'],
        'template' => '<div class="input-group">{input}
                            <div class="input-group-append">
                                <span class="input-group-text">{image}</span>
                            </div>
                       </div>'
    ]);
    echo Html::tag('p', 'Bạn chưa nhận được mã OTP? <a href="#">Gửi lại</a>');
    echo Html::submitButton('Xác thực', ['class' => 'btn btn-submit btn-block']);
    ActiveForm::end();
}
?>
