<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model userbackend\models\PromotionUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promotion-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'is_used') ?>

    <?php // echo $form->field($model, 'used_order_id') ?>

    <?php // echo $form->field($model, 'used_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'promotion_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
