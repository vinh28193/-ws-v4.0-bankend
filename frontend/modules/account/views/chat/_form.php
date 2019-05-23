<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\ChatMongoWs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chat-mongo-ws-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'success') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'user_email') ?>

    <?= $form->field($model, 'user_name') ?>

    <?= $form->field($model, 'user_app') ?>

    <?= $form->field($model, 'user_request_suorce') ?>

    <?= $form->field($model, 'request_ip') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'Order_path') ?>

    <?= $form->field($model, 'is_send_email_to_customer') ?>

    <?= $form->field($model, 'type_chat') ?>

    <?= $form->field($model, 'is_customer_vew') ?>

    <?= $form->field($model, 'is_employee_vew') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
