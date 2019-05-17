<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/* @var yii\web\View $this */

$this->title = 'Đăng nhập';
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>

<p> Vui lòng điền vào các trường sau để đăng nhập :</p>


<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>


<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>


    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [ 'class' => 'payment-form']]); ?>
    <div class="form-group">
        <?= $form->field($model, 'email',['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->textInput(['autofocus' => true])->input('text', ['placeholder' => " Nhập Email"])?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'password', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
    </div>
    <div class="check-info">
        <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'form-check-input']) ?>
        <div style="color:#999;margin:1em 0">
            <?= Html::a('Nếu bạn quên mật khẩu, bạn có thể khôi phục nó ?', ['secure/request-password-reset']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="other-login">
        <div class="text-center"><span class="or">Hoặc đăng nhâp qua</span></div>
        <div class="social-button-ws">
            <?php $authAuthChoice = AuthChoice::begin([  'baseAuthUrl' => ['secure/auth'] , 'popupMode' => false, ]); ?>
                    <div class="social-button">
                 <?php foreach ($authAuthChoice->getClients() as $client): ?>
                     <?= $authAuthChoice->clientLink($client) ?>
                 <?php endforeach; ?>
                    </div>
             <?php AuthChoice::end(); ?>

        </div>

        <p>Quý khách chưa có tài khoản
            <?php echo Html::a('Đăng ký ngay', ['/secure/register']); ?>
        </p>
    </div>

