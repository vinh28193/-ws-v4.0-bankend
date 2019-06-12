<?php

use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */
/* @var frontend\modules\payment\Payment $payment */

$id = Html::getInputId($shippingForm, 'other_receiver');
$js = <<< JS
    
    var showReceiver = function(element) {
        element  = $(element)
        var checked = element.is(':checked');
        $('div.receiver-form').css('display',checked ? 'block' :'none');
    };
    var otherReceiver =  $('#$id');
    showReceiver(otherReceiver);
    ws.initEventHandler('payment-form','change','change','#$id',function(event) {
        showReceiver(this);
    });
    
    $(document).on("beforeSubmit", "form.payment-form", function (e) {
        e.preventDefault();
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) 
        {
            return false;
        }
        $('.checkout-step li').removeClass('active');
        $('.checkout-step li').each(function (k, v) {
            if (k === 2) {
                $(v).addClass('active');
            }
        });
        $('#step_checkout_1').css('display', 'none');
        $('#step_checkout_2').css('display', 'none');
        $('#step_checkout_3').css('display', 'block');
        window.scrollTo(0, 0);
        // send data to actionSave by ajax request.
        return false; // Cancel form submitting.
    });
JS;
$this->registerJs($js);
?>
<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span><?= Yii::t('frontend', 'Login'); ?></span></li>
        <li class="active"><i>2</i><span><?= Yii::t('frontend', 'Shipping address'); ?></span></li>
        <li><i>3</i><span><?= Yii::t('frontend', 'Payment'); ?></span></li>
    </ul>
    <div class="step-2-content row">
        <div id="step_checkout_2" class="col-md-8">
            <div class="title">Thông tin mua hàng</div>
            <div class="payment-box">
                <?php
                $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'payment-form'
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnChange' => true,
                    'validateOnBlur' => true,
                    'validateOnType' => true,
                    'validationUrl' => '/checkout/shipping/validate',
                ]);
                echo Html::activeHiddenInput($shippingForm, 'customer_id');
                echo Html::activeHiddenInput($shippingForm, 'buyer_country_id');
                echo Html::activeHiddenInput($shippingForm, 'cartIds');
                echo Html::activeHiddenInput($shippingForm, 'checkoutType');

                echo Html::beginTag('div', ['class' => 'buyer-form']);
                echo $form->field($shippingForm, 'buyer_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Họ và tên']);

                echo $form->field($shippingForm, 'buyer_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Số điện thoại']);

                echo $form->field($shippingForm, 'buyer_email', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Địa chỉ email']);

                echo $form->field($shippingForm, 'buyer_province_id', [
                    'template' => '<i class="icon globe"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList($provinces, [
                    'prompt' => 'Chọn thành phố',
                ]);

                echo Html::hiddenInput('hiddenBuyerDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenBuyerDistrictId']);

                echo $form->field($shippingForm, 'buyer_district_id', [
                    'template' => '<i class="icon city"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'buyer_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district']),
                        'loadingText' => 'Loading District ...',
                        'initialize' => true,
                        'params' => ['hiddenBuyerDistrictId']
                    ]
                ]);
                echo $form->field($shippingForm, 'buyer_address', [
                    'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Địa chỉ chi tiết']);

                echo $form->field($shippingForm, 'note_by_customer', [
//                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textarea(['rows' => 3, 'placeholder' => 'Ghi chú thêm ( không bắt buộc)']);

                echo Html::endTag('div');

                echo $form->field($shippingForm, 'other_receiver', [
                    'template' => '{input}{hint}{error}',
                    'options' => [
                        'class' => 'check-info',
                    ]
                ])->checkbox()->label('Thông tin người nhận hàng khác người đặt hàng');

                echo Html::beginTag('div', ['class' => 'receiver-form']);

                echo $form->field($shippingForm, 'receiver_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Họ và tên']);

                echo $form->field($shippingForm, 'receiver_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Số điện thoại']);

                echo $form->field($shippingForm, 'receiver_email', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Địa chỉ email']);

                echo $form->field($shippingForm, 'receiver_province_id', [
                    'template' => '<i class="icon city"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList(array_merge(['Chọn thành phố'], $provinces));

                echo Html::hiddenInput('hiddenReceiverDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenReceiverDistrictId']);

                echo $form->field($shippingForm, 'receiver_district_id', [
                    'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'receiver_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district']),
                        'loadingText' => 'Loading District ...',
                        'initialize' => true,
                        'params' => ['hiddenReceiverDistrictId']
                    ]
                ]);
                echo $form->field($shippingForm, 'receiver_address', [
                    'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput(['placeholder' => 'Địa chỉ chi tiết']);

                echo Html::endTag('div');

                echo Html::submitButton('Chọn hình thức thanh toán', ['class' => 'btn btn-payment btn-block', 'id' => 'btnNextStep3']);
                ActiveForm::end();
                ?>
            </div>
        </div>
        <div id="step_checkout_3" class="col-md-8" style="display: none">
            <div class="title">Phương thức thanh toán</div>
            <div class="payment-box payment-step3">
                <?php echo $payment->initPaymentView(); ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php echo $this->render('_cart', ['payment' => $payment]) ?>
        </div>
    </div>
</div>
