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


if (!$statusOtp){
    if(!$isValid && $transactionDetail['type'] ==='PAY_ORDER' && $transactionDetail['status'] === 4){

    }
}
echo Html::tag('div', 'Xác thực OTP', ['class' => 'modal-title']);
if ($msg !== null) {
    echo Html::tag('p', $msg);
}
$form = ActiveForm::begin([
    'options' => [
        'id' => 'otpVerifyForm'
    ]
]);

echo $form->field($otpVerifyForm,'optCode')->textInput();
echo $form->field($otpVerifyForm,'password')->passwordInput();
echo Html::tag('p','Bạn chưa nhận được mã OTP? <a href="#">Gửi lại</a>' );
?>

<div class="modal-title">Xác thực OTP</div>
<p><?= $msg ?></p>
<form>
    <div class="form-group">
        <label>Mã OTP</label>
        <input type="text" class="form-control text-center">
    </div>
    <div class="form-group">
        <label>Mã xác nhận</label>
        <div class="input-group">
            <input type="text" class="form-control">
            <div class="input-group-append">
                <span class="input-group-text"><img
                            src="https://thefallenbrain.files.wordpress.com/2016/05/input-black.gif"/></span>
            </div>
        </div>
    </div>
    <p>Bạn chưa nhận được mã OTP? <a href="#">Gửi lại</a></p>
    <button type="button" class="btn btn-submit btn-block">Xác thực</button>
</form>
