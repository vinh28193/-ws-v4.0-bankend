<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\payment\models\SecureForm;

/* @var yii\web\View $this */
$model = new SecureForm();
$modelLogin = new \frontend\models\LoginForm();
$modelSignup = new \frontend\models\SignupForm();

$formId = 'secureForm';
$isNewCheckBox = Html::getInputName($model, 'isNew');
$passWordName = Html::getInputName($model, 'password');
$js = <<<JS

$(document).on("change", "input:radio[name='$isNewCheckBox']:checked", function (e) {
    e.preventDefault();
    console.log($(this).val());
    return false;
});

$(document).on("beforeSubmit", "form#$formId", function (e) {
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
});
JS;

$this->registerJs($js);
?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li class="active"><i>1</i><span>Đăng nhập</span></li>
        <li><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-1-content">
        <div class="title">Nhập số điện thoại/ Email để tiếp tục thanh toán</div>
        <div class="auth-form">
            <div class="form-group">
                <i class="icon email"></i>
                <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="check-member">
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="member" value="member" name="check-member" checked>
                    <label class="form-check-label" for="member">Đã là thành viên Weshop</label>
                </div>
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="new-member" value="new-member" name="check-member">
                    <label class="form-check-label" for="new-member">Tôi là khách hàng mới</label>
                </div>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control"  name="first_name" placeholder="Tên">
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control"   name="last_name" placeholder="Họ">
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon phone"></i>
                <input type="text" class="form-control " name="phone" placeholder="Số điện thoại">
            </div>
            <div class="form-group">
                <i class="icon password"></i>
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu">
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon password"></i>
                <input type="password" class="form-control"  name="re-password" placeholder="Nhập lại mật khẩu">
            </div>
            <div class="check-info">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" checked>
                    <label class="form-check-label" for="remember">Ghi nhớ</label>
                </div>
                <a href="#" class="forgot">Quên mật khẩu?</a>
            </div>
            <button type="button" id="loginToCheckout" class="btn btn-login">Đăng nhập để mua hàng</button>
        </div>
        <div class="other-login">
            <div class="text-center"><span class="or">Hoặc đăng nhâp qua</span></div>
            <div class="social-button">
                <a href="#" class="btn btn-fb">
                    <i class="social-icon fb"></i>
                    <span>Facebook</span>
                </a>
                <a href="#" class="btn btn-google">
                    <i class="social-icon google"></i>
                    <span>Google</span>
                </a>
            </div>
        </div>
    </div>
</div>

