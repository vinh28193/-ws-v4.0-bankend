<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\authclient\widgets\AuthChoice;

/* @var yii\web\View $this */

$this->title = 'Thay đổi mật khảu';
$session = Yii::$app->session;
//$flashes = $session->getAllFlashes();

echo Html::tag('div',Html::tag('span',$this->title),['class' => 'title'])
?>

<p> Vui lòng điền đầy đủ thông tin vào các trường sau:</p>


<?php $form = ActiveForm::begin(['id' => 'change-password-form', 'options' => [ 'class' => 'payment-form']]); ?>
<div class="form-group">
    <?= $form->field($model, 'passwordOld',['template' => " <i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->textInput(['autofocus' => true])->input('text', ['placeholder' => "Nhập mật khẩu hiện tại"])?>
</div>
<div class="form-group">
    <?= $form->field($model, 'passwordNew', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
</div>
<div class="form-group">
    <?= $form->field($model, 'replacePassword', ['template' => "<i class=\"icon password\"></i>{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => "Mật khẩu"]) ?>
</div>

<div class="form-group" style="width: 100%">
    <?= Html::submitButton('Thay đổi', ['class' => 'btn btn-warning text-white sty-btn', 'name' => 'login-button']) ?>
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

