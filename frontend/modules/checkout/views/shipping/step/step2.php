<?php

use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var common\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */
/* @var common\payment\Payment $payment */

?>
<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span>Đăng nhập</span></li>
        <li class="active"><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-2-content row">
        <div class="col-md-8">
            <div class="title">Thông tin mua hàng</div>
            <div class="payment-box">
                <?php
                $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'payment-form'
                    ],
                ]);
                echo Html::activeHiddenInput($shippingForm, 'customer_id');

                echo $form->field($shippingForm, 'buyer_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_phone', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_province_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList($provinces);

                echo $form->field($shippingForm, 'buyer_district_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'options' => ['id' => 'district_id'],
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'buyer_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district'])
                    ]
                ]);

                echo $form->field($shippingForm, 'note_by_customer', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textarea(['rows' => 3, 'placeholder' => 'Ghi chú thêm ( không bắt buộc)']);

                echo $form->field($shippingForm, 'save_my_address', [
                    'template' => '{input}{hint}{error}',
                    'options' => [
                        'class' => 'check-info'
                    ]
                ])->checkbox();

                echo '<a href="#" class="other-receiver"><i class="fas fa-check-circle"></i> Thông tin người nhận
                hàng khác người đặt hàng</a>';

                echo $form->field($shippingForm, 'receiver_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_phone', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_province_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList($provinces);

                echo $form->field($shippingForm, 'receiver_district_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'receiver_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district'])
                    ]
                ]);
                echo Html::submitButton('Chọn hình thức thanh toán', ['class' => 'btn btn-payment btn-block']);
                ActiveForm::end();
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php echo $this->render('_cart',['payment' => $payment]) ?>
        </div>
    </div>
</div>
