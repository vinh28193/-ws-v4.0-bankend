<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'type_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'customer_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'portal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchased')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_shipped')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stockin_us')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stockout_us')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stockin_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stockout_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'at_customer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'returned')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cancelled')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_quotation')->textInput() ?>

    <?= $form->field($model, 'quotation_status')->textInput() ?>

    <?= $form->field($model, 'quotation_note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_country_id')->textInput() ?>

    <?= $form->field($model, 'receiver_country_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_province_id')->textInput() ?>

    <?= $form->field($model, 'receiver_province_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_district_id')->textInput() ?>

    <?= $form->field($model, 'receiver_district_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_post_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_address_id')->textInput() ?>

    <?= $form->field($model, 'note_by_customer')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'seller_id')->textInput() ?>

    <?= $form->field($model, 'seller_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_store')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_final_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_origin_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_price_amount_origin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_paid_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_refund_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_counpon_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_promotion_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_fee_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_custom_fee_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_origin_tax_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_origin_shipping_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_weshop_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_intl_shipping_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_delivery_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_packing_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_inspection_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_insurance_fee_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_vat_amount_local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exchange_rate_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exchange_rate_purchase')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_purchase')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sale_support_id')->textInput() ?>

    <?= $form->field($model, 'support_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_email_sent')->textInput() ?>

    <?= $form->field($model, 'is_sms_sent')->textInput() ?>

    <?= $form->field($model, 'difference_money')->textInput() ?>

    <?= $form->field($model, 'coupon_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'revenue_xu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xu_count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xu_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xu_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xu_log')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promotion_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_weight_temporary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_order_id')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purchase_transaction_id')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purchase_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_account_id')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purchase_account_email')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purchase_card')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purchase_amount_buck')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_amount_refund')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_refund_transaction_id')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_quantity')->textInput() ?>

    <?= $form->field($model, 'total_purchase_quantity')->textInput() ?>

    <?= $form->field($model, 'remove')->textInput() ?>

    <?= $form->field($model, 'supported')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ready_purchase')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supporting')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'check_update_payment')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
