<?php

/* @var yii\web\View $this */

$css =<<<CSS
    .btn-continue {
        height: 36px;
        border-radius: 2px;
        background-image: linear-gradient(3deg, #2b96b6, #2b96b6);
        color: #fff;
        font-size: 16px;
        font-weight: 500;
        width: 100%;
        margin-top: 0.25rem;
    }
CSS;
$this->registerCss($css);
?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li class="active"><i>1</i><span><?= Yii::t('frontend', 'Login'); ?></span></li>
        <li><i>2</i><span><?= Yii::t('frontend', 'Shipping address'); ?></span></li>
        <li><i>3</i><span><?= Yii::t('frontend', 'Payment'); ?></span></li>
    </ul>
    <div class="step-1-content">
        <div class="title"><?= Yii::t('frontend', 'Enter the phone number / Email to continue payment'); ?></div>
        <div class="auth-form">
            <div class="form-group">
                <i class="icon email"></i>
                <input type="text" class="form-control" name="email" placeholder="<?= Yii::t('frontend', 'Email'); ?>">
                <label data-href="error" style="color: red" id="email-error"></label>
            </div>
            <div class="check-member">
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="member" value="member" name="check-member" checked>
                    <label class="form-check-label"
                           for="member"><?= Yii::t('frontend', 'Already a weshop member'); ?></label>
                </div>
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="new-member" value="new-member" name="check-member">
                    <label class="form-check-label"
                           for="new-member"><?= Yii::t('frontend', 'I am a new customer'); ?></label>
                </div>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control" name="first_name" placeholder="<?= Yii::t('frontend', 'First name'); ?>">
                <label data-href="error" style="color: red" id="first_name-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control" name="last_name" placeholder="<?= Yii::t('frontend', 'Last name'); ?>">
                <label data-href="error" style="color: red" id="last_name-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon phone"></i>
                <input type="text" class="form-control " name="phone" placeholder="<?= Yii::t('frontend', 'Phone'); ?>">
                <label data-href="error" style="color: red" id="phone-error"></label>
            </div>
            <div class="form-group">
                <i class="icon password"></i>
                <input type="password" class="form-control" name="password" placeholder="<?= Yii::t('frontend', 'Password'); ?>">
                <label data-href="error" style="color: red" id="password-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon password"></i>
                <input type="password" class="form-control" name="replacePassword" placeholder="<?= Yii::t('frontend', 'Replace password'); ?>">
                <label data-href="error" style="color: red" id="replacePassword-error"></label>
            </div>
            <div class="check-info">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="remember" checked>
                    <label class="form-check-label" for="remember"><?= Yii::t('frontend', 'Remember'); ?></label>
                </div>
                <a href="#" class="forgot"><?=Yii::t('frontend','Forgot password?');?></a>
            </div>
            <button type="button" id="loginToCheckout" class="btn btn-login"><?=Yii::t('frontend','Login to purchase');?></button>
            <button type="button" id="continueAsGuest" class="btn btn-continue"><?=Yii::t('frontend','Continue as guest');?></button>
        </div>
        <div class="other-login">
            <div class="text-center"><span class="or"><?=Yii::t('frontend','Or sign in through');?></span></div>
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

