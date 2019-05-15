<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;
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
    <?= $form->field($model, 'last_name',['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => "Họ Tên"]) ?>
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
        <?php $authAuthChoice = AuthChoice::begin([  'baseAuthUrl' => ['secure/auth'] , 'popupMode' => false, ]); ?>
        <div class="social-button">
            <?php foreach ($authAuthChoice->getClients() as $client): ?>
                <?= $authAuthChoice->clientLink($client) ?>
            <?php endforeach; ?>
        </div>
        <?php AuthChoice::end(); ?>
    </div>
</div>
