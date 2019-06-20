<?php
/**
 * @var \frontend\models\SignupForm $model
 * @var string $token_refresh
 * @var string $user_id
 */
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
/* @var yii\web\View $this */

$this->title = Yii::t('frontend','Register');
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>

<p> <?= Yii::t('frontend','There is only one final step to complete the registration') ?>:</p>

<?php $form = ActiveForm::begin([
    'id' => 'form-signup',
    'options' => [
        'class' => 'payment-form',
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnChange' => true,
    'validateOnBlur' => true,
    'validateOnType' => true,
    'validationUrl' => '/secure/register-validate',
]); ?>
<div class="form-group">
    <?= $form->field($model, 'first_name', ['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => "Nhập Họ"]) ?>
</div>
<div class="form-group">
    <?= $form->field($model, 'last_name', ['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => "Nhập Tên"]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'email', ['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput()->input('email', ['placeholder' => "Nhập địa chỉ email"]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'phone', ['template' => " <i class=\"icon phone\"></i>{input}\n{hint}\n{error}"])->textInput()->input('number', ['placeholder' => "Số điện thoại"]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'replacePassword', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Nhập lại mật khẩu"]) ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Đăng ký', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>

