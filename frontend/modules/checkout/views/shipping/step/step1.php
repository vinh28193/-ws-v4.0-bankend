<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use frontend\modules\checkout\models\SecureForm;

/* @var yii\web\View $this */
$model = new SecureForm();
?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li class="active"><i>1</i><span>Đăng nhập</span></li>
        <li><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-1-content">
        <div class="title">Nhập số điện thoại/ Email để tiếp tục thanh toán</div>
        <?php
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'auth-form'
            ],

        ]);
        echo $form->field($model, 'loginId', [
            'template' => '<i class="icon user"></i>{input}{hint}{error}',
            'options' => [
                'class' => 'form-group checkout-2-form'
            ]
        ])
            ->textInput([])
        ?>
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

