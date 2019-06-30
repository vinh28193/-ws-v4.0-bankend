<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/* @var yii\web\View $this */

$this->title = Yii::t('frontend','Login');
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>

<p><?= Yii::t('frontend','Please fill all field so login:') ?></p>


<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Save!') ?></h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>


<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i><?= Yii::t('frontend','Save!') ?></h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>


    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [ 'class' => 'payment-form']]); ?>
    <div class="form-group">
        <?= $form->field($model, 'loginId',['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput(['autofocus' => true])->input('text', ['placeholder' => Yii::t('frontend', 'Email')])?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => Yii::t('frontend','Password')]) ?>
    </div>
    <div class="check-info">
        <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'form-check-input'])->label(Yii::t('frontend','Remember Me')) ?>
        <div style="color:#999;margin:1em 0">
            <?= Yii::t('frontend','Are you forget password? You can <a href="secure/request-password-reset">reset it now!</a>') ?>
        </div>
    </div>

    <div class="form-group" style="width: 100%">
        <?= Html::submitButton(Yii::t('frontend','Login'), ['class' => 'btn btn-warning text-white sty-btn', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="other-login">
        <div class="text-center"><span class="or"><?= Yii::t('frontend','Or login with:') ?></span></div>
        <div class="social-button-ws">
            <button onclick="smsLogin();" class="btn btn-fb" style="width: 100%;"><?= Yii::t('frontend','Login via SMS') ?></button>
        </div>

        <p><?= Yii::t('frontend','Are you not have account? <a href="/signup.html">Sign up</a>') ?>
        </p>
    </div>

