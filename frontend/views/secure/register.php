<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/* @var yii\web\View $this */

$this->title = Yii::t('frontend', 'Sign Up');
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div', Html::tag('span', $this->title), ['class' => 'title'])
?>

<p><?= Yii::t('frontend','Please fill all field so sign up:') ?></p>

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
    <?= $form->field($model, 'first_name', ['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => Yii::t('frontend','Full Name')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'email', ['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput()->input('email', ['placeholder' =>  Yii::t('frontend','Email')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'phone', ['template' => " <i class=\"icon phone\"></i>{input}\n{hint}\n{error}"])->textInput()->input('number', ['placeholder' =>  Yii::t('frontend','Phone')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' =>  Yii::t('frontend','Password')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'replacePassword', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' =>  Yii::t('frontend','Re-Password')]) ?>
</div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('frontend', 'Sign Up'), ['class' => 'btn btn-primary', 'style' => 'width: 100%', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
<div class="other-login">
    <div class="text-center"><span class="or"><?= Yii::t('frontend','or') ?></span></div>
    <div class="social-button-ws">
        <button onclick="smsLogin();" class="btn btn-fb" style="width: 100%;"><?= Yii::t('frontend','Login via SMS') ?></button>
    </div>
    <div class="text-center" style="color:#999;margin:1em 0">
        <?= Yii::t('frontend','Are you forget password? You can <a href="secure/request-password-reset">reset it now!</a>') ?>
    </div>
</div>
