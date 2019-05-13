<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var yii\web\View $this */

$this->title = 'Đăng nhập';

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>
<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
    'class' => 'payment-form'
]
]); ?>
<div class="form-group">
    <?= $form->field($model, 'email',['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput()->input('text', ['placeholder' => "Email hoặc số điện thoại"])?>
</div>
<div class="form-group">
    <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
</div>
<div class="check-info">
    <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'form-check-input']) ?>
    <div style="color:#999;margin:1em 0">
        <?= Html::a('Quên mật khẩu ?', ['site/request-password-reset']) ?>.
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
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
    <p>Quý khách chưa có tài khoản
        <?php echo Html::a('Đăng ký ngay', ['/secure/register']); ?>
    </p>
</div>
