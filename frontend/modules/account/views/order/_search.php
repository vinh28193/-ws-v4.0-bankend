<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model userbackend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ordercode') ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'type_order') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'customer_type') ?>

    <?php // echo $form->field($model, 'portal') ?>

    <?php // echo $form->field($model, 'utm_source') ?>

    <?php // echo $form->field($model, 'new') ?>

    <?php // echo $form->field($model, 'purchase_start') ?>

    <?php // echo $form->field($model, 'purchased') ?>

    <?php // echo $form->field($model, 'seller_shipped') ?>

    <?php // echo $form->field($model, 'stockin_us') ?>

    <?php // echo $form->field($model, 'stockout_us') ?>

    <?php // echo $form->field($model, 'stockin_local') ?>

    <?php // echo $form->field($model, 'stockout_local') ?>

    <?php // echo $form->field($model, 'at_customer') ?>

    <?php // echo $form->field($model, 'returned') ?>

    <?php // echo $form->field($model, 'cancelled') ?>

    <?php // echo $form->field($model, 'lost') ?>

    <?php // echo $form->field($model, 'current_status') ?>

    <?php // echo $form->field($model, 'is_quotation') ?>

    <?php // echo $form->field($model, 'quotation_status') ?>

    <?php // echo $form->field($model, 'quotation_note') ?>

    <?php // echo $form->field($model, 'receiver_email') ?>

    <?php // echo $form->field($model, 'receiver_name') ?>

    <?php // echo $form->field($model, 'receiver_phone') ?>

    <?php // echo $form->field($model, 'receiver_address') ?>

    <?php // echo $form->field($model, 'receiver_country_id') ?>

    <?php // echo $form->field($model, 'receiver_country_name') ?>

    <?php // echo $form->field($model, 'receiver_province_id') ?>

    <?php // echo $form->field($model, 'receiver_province_name') ?>

    <?php // echo $form->field($model, 'receiver_district_id') ?>

    <?php // echo $form->field($model, 'receiver_district_name') ?>

    <?php // echo $form->field($model, 'receiver_post_code') ?>

    <?php // echo $form->field($model, 'receiver_address_id') ?>

    <?php // echo $form->field($model, 'note_by_customer') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'seller_id') ?>

    <?php // echo $form->field($model, 'seller_name') ?>

    <?php // echo $form->field($model, 'seller_store') ?>

    <?php // echo $form->field($model, 'total_final_amount_local') ?>

    <?php // echo $form->field($model, 'total_amount_local') ?>

    <?php // echo $form->field($model, 'total_origin_fee_local') ?>

    <?php // echo $form->field($model, 'total_price_amount_origin') ?>

    <?php // echo $form->field($model, 'total_paid_amount_local') ?>

    <?php // echo $form->field($model, 'total_refund_amount_local') ?>

    <?php // echo $form->field($model, 'total_counpon_amount_local') ?>

    <?php // echo $form->field($model, 'total_promotion_amount_local') ?>

    <?php // echo $form->field($model, 'total_fee_amount_local') ?>

    <?php // echo $form->field($model, 'total_custom_fee_amount_local') ?>

    <?php // echo $form->field($model, 'total_origin_tax_fee_local') ?>

    <?php // echo $form->field($model, 'total_origin_shipping_fee_local') ?>

    <?php // echo $form->field($model, 'total_weshop_fee_local') ?>

    <?php // echo $form->field($model, 'total_intl_shipping_fee_local') ?>

    <?php // echo $form->field($model, 'total_delivery_fee_local') ?>

    <?php // echo $form->field($model, 'total_packing_fee_local') ?>

    <?php // echo $form->field($model, 'total_inspection_fee_local') ?>

    <?php // echo $form->field($model, 'total_insurance_fee_local') ?>

    <?php // echo $form->field($model, 'total_vat_amount_local') ?>

    <?php // echo $form->field($model, 'exchange_rate_fee') ?>

    <?php // echo $form->field($model, 'exchange_rate_purchase') ?>

    <?php // echo $form->field($model, 'currency_purchase') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'sale_support_id') ?>

    <?php // echo $form->field($model, 'support_email') ?>

    <?php // echo $form->field($model, 'is_email_sent') ?>

    <?php // echo $form->field($model, 'is_sms_sent') ?>

    <?php // echo $form->field($model, 'difference_money') ?>

    <?php // echo $form->field($model, 'coupon_id') ?>

    <?php // echo $form->field($model, 'revenue_xu') ?>

    <?php // echo $form->field($model, 'xu_count') ?>

    <?php // echo $form->field($model, 'xu_amount') ?>

    <?php // echo $form->field($model, 'xu_time') ?>

    <?php // echo $form->field($model, 'xu_log') ?>

    <?php // echo $form->field($model, 'promotion_id') ?>

    <?php // echo $form->field($model, 'total_weight') ?>

    <?php // echo $form->field($model, 'total_weight_temporary') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'purchase_assignee_id') ?>

    <?php // echo $form->field($model, 'purchase_order_id') ?>

    <?php // echo $form->field($model, 'purchase_transaction_id') ?>

    <?php // echo $form->field($model, 'purchase_amount') ?>

    <?php // echo $form->field($model, 'purchase_account_id') ?>

    <?php // echo $form->field($model, 'purchase_account_email') ?>

    <?php // echo $form->field($model, 'purchase_card') ?>

    <?php // echo $form->field($model, 'purchase_amount_buck') ?>

    <?php // echo $form->field($model, 'purchase_amount_refund') ?>

    <?php // echo $form->field($model, 'purchase_refund_transaction_id') ?>

    <?php // echo $form->field($model, 'total_quantity') ?>

    <?php // echo $form->field($model, 'total_purchase_quantity') ?>

    <?php // echo $form->field($model, 'remove') ?>

    <?php // echo $form->field($model, 'version') ?>

    <?php // echo $form->field($model, 'mark_supporting') ?>

    <?php // echo $form->field($model, 'supported') ?>

    <?php // echo $form->field($model, 'ready_purchase') ?>

    <?php // echo $form->field($model, 'supporting') ?>

    <?php // echo $form->field($model, 'check_update_payment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
