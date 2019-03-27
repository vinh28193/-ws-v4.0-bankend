<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modelsMongo\PaymentLogWS */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="PaymentLogWS-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'timestamp') ?>

    <?= $form->field($model, 'data_input') ?>

    <?= $form->field($model, 'data_output') ?>

    <?= $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
