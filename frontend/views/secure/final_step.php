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

$this->title = Yii::t('frontend','Sign Up');
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>

<p> <?= Yii::t('frontend','There is only one final step to complete the registration') ?>:</p>

<?php $form = ActiveForm::begin([
    'id' => 'form-signup',
    'action' => '/signup.html',
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
    <?= $form->field($model, 'email', ['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput()->input('email', ['placeholder' => Yii::t('frontend','Email')]) ?>
</div>

<div class="form-group">
    <i class="icon phone"></i><input type="text" disabled class="form-control" value="<?= $model->phone ?>">
</div>
<div style="display: none">
    <?= $form->field($model, 'phone', [])->hiddenInput()->input('hidden', ['placeholder' => Yii::t('frontend','Phone')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' =>  Yii::t('frontend','Password')]) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'replacePassword', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => Yii::t('frontend','Re-Password')]) ?>
</div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('frontend', 'Sign Up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>

