<?php

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
        ]
    ]);
    echo Html::activeHiddenInput($otpVerifyForm, 'otpReceive');
    echo Html::activeHiddenInput($otpVerifyForm, 'transactionCode');
    echo $form->field($otpVerifyForm, 'optCode')->textInput();
    echo $form->field($otpVerifyForm, 'password')->passwordInput();
    echo Html::tag('p', 'Bạn chưa nhận được mã OTP? <a href="#">Gửi lại</a>');
    echo Html::button('Xác thực', ['class' => 'btn btn-submit btn-block']);
    ActiveForm::end();
}
?>
