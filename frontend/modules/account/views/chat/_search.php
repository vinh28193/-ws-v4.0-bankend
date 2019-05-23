<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chat-mongo-ws-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'success') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_email') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'user_app') ?>

    <?php // echo $form->field($model, 'user_request_suorce') ?>

    <?php // echo $form->field($model, 'request_ip') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'user_avatars') ?>

    <?php // echo $form->field($model, 'Order_path') ?>

    <?php // echo $form->field($model, 'is_send_email_to_customer') ?>

    <?php // echo $form->field($model, 'type_chat') ?>

    <?php // echo $form->field($model, 'is_customer_vew') ?>

    <?php // echo $form->field($model, 'is_employee_vew') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
