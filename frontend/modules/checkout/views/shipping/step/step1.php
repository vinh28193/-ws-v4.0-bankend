<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\payment\models\SecureForm;

/* @var yii\web\View $this */
$model = new SecureForm();

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
        <?php
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'auth-form',
                'id' => $formId,
            ],

        ]);
        echo $form->field($model, 'loginId', [
            'template' => '<i class="icon user"></i>{input}{hint}{error}',
            'options' => [
                'class' => 'form-group'
            ]
        ])
            ->textInput(['placeholder' => 'Email hoặc số điện thoại']);
        echo $form->field($model, 'isNew', [
            'template' => '{input}{hint}{error}',
            'options' => [
                'class' => 'check-member'
            ]
        ])->radioList([
            'yes' => 'Đã là thành viên Weshop',
            'no' => 'Tôi là khách hàng mới'
        ]);

        echo $form->field($model, 'password', [
            'template' => '<i class="icon password"></i>{input}{hint}{error}',
            'options' => [
                'class' => 'form-group'
            ]
        ])->textInput(['placeholder' => 'Email hoặc số điện thoại']);

        echo $form->field($model, 'rememberMe', [
            'template' => '{input}{hint}{error}',
            'options' => [
                'class' => 'check-info'
            ]
        ])->checkbox();
        echo Html::submitButton('Đăng nhập để mua hàng', ['class' => 'btn btn-login', 'style' => 'margin-top: 0.75rem;']);
        ActiveForm::end();
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

