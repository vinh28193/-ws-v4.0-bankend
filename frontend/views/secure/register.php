<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="title">
    <span>Đăng ký</span>
    <i class="wall"></i>
    <?php echo Html::a('Đăng nhập', ['/secure/login']); ?>
</div>
<?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => [
    'class' => 'payment-form'
]
]); ?>
<div class="form-group">
    <?= $form->field($model, 'first_name',['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => "Tên đầu"]) ?>
</div>
<div class="form-group">
    <?= $form->field($model, 'last_name',['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => "Tên Cuối"]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'email',['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput()->input('email', ['placeholder' => "Nhập địa chỉ email"])?>
</div>

<div class="form-group">
    <?= $form->field($model, 'phone',['template' => " <i class=\"icon phone\"></i>{input}\n{hint}\n{error}"])->textInput()->input('number', ['placeholder' => "Số điện thoại"]) ?>
</div>

<div class="form-group">
<?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
</div>

<div class="form-group">
<?= $form->field($model, 'replacePassword', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Nhập lại mật khẩu"]) ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
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
