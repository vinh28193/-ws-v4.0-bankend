<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\PaymentLogWS */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="PaymentLogWS-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'success') ?>

    <?= $form->field($model, 'timestamp') ?>

    <?= $form->field($model, 'path') ?>

    <?= $form->field($model, 'data') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
