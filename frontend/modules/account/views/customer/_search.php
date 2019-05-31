<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model userbackend\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'link_verify') ?>

    <?php // echo $form->field($model, 'email_verified') ?>

    <?php // echo $form->field($model, 'phone_verified') ?>

    <?php // echo $form->field($model, 'last_order_time') ?>

    <?php // echo $form->field($model, 'note_by_employee') ?>

    <?php // echo $form->field($model, 'type_customer') ?>

    <?php // echo $form->field($model, 'access_token') ?>

    <?php // echo $form->field($model, 'auth_client') ?>

    <?php // echo $form->field($model, 'verify_token') ?>

    <?php // echo $form->field($model, 'reset_password_token') ?>

    <?php // echo $form->field($model, 'store_id') ?>

    <?php // echo $form->field($model, 'active_shipping') ?>

    <?php // echo $form->field($model, 'total_xu') ?>

    <?php // echo $form->field($model, 'total_xu_start_date') ?>

    <?php // echo $form->field($model, 'total_xu_expired_date') ?>

    <?php // echo $form->field($model, 'usable_xu') ?>

    <?php // echo $form->field($model, 'usable_xu_start_date') ?>

    <?php // echo $form->field($model, 'usable_xu_expired_date') ?>

    <?php // echo $form->field($model, 'last_use_xu') ?>

    <?php // echo $form->field($model, 'last_use_time') ?>

    <?php // echo $form->field($model, 'last_revenue_xu') ?>

    <?php // echo $form->field($model, 'last_revenue_time') ?>

    <?php // echo $form->field($model, 'verify_code') ?>

    <?php // echo $form->field($model, 'verify_code_expired_at') ?>

    <?php // echo $form->field($model, 'verify_code_count') ?>

    <?php // echo $form->field($model, 'verify_code_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'remove') ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend','Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('frontend','Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
