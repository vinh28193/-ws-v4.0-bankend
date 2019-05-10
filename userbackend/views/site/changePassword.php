<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChangePasswordForm */
/* @var $form ActiveForm */

$this->title = 'Change Password';
?>
<div class="customer-changePassword">
    <div class="row">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'passwordOld')->passwordInput() ?>
            <?= $form->field($model, 'passwordNew')->passwordInput() ?>
            <?= $form->field($model, 'RepeatPassword')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Change', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>