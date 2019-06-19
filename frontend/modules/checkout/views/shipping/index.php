<?php

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\payment\Payment $payment */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */


$showStep = true;
$activeStep = 2;
$otherReceiver = Html::getInputId($shippingForm, 'other_receiver');
$js = <<< JS
    
    var showReceiver = function(element) {
        element  = $(element);
        var checked = element.is(':checked');
        $('div.receiver-form').css('display',checked ? 'block' :'none');
    };
    var otherReceiver =  $('#$otherReceiver');
    showReceiver(otherReceiver);
    ws.initEventHandler('payment-form','change','change','#$otherReceiver',function(event) {
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
        ws.payment.activePaymentStep(3);
        window.scrollTo(0, 0);
        // send data to actionSave by ajax request.
        return false; // Cancel form submitting.
    });
    var depdropChange = function(event, id, value, count) { 
        var log = function(v){
            console.log(v);
        }
        log(id); log(value); log(count); 
    }
JS;
$this->registerJs($js);
?>
<style type="text/css">
    .btn-shipping {
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        border-radius: 3px;
        border: 1px solid #00a3d3;
        background-image: linear-gradient(180deg, #56c7dc 0%, #2b96b6 100%);
    }

</style>
<div class="container">
    <div class="card card-checkout">
        <div class="card-body">
            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'class' => 'shipping-form'
                ],
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validationUrl' => '/checkout/shipping/validate',
            ]);
            ?>

            <div class="row shipping-address">
                <div class="col-md-12">
                    <div class="card-title">
                        <i class="fa fa-user"></i>Thông tin người mua hàng
                    </div>
                </div>
                <div class="col-md-12">
                    <?php
                    $shippingAddress = $shippingForm->getReceiverAddress();

                    if (!empty($shippingAddress)) {
                        $shippingAddress = \yii\helpers\ArrayHelper::map($shippingAddress, 'id', function ($address) {
                            /** @var $address common\models\Address */
                            return implode(' ', [$address->first_name, $address->last_name]);
                        });
                        echo $form->field($shippingForm, 'receiver_address_id')->radioList($shippingAddress)->label(false);
                    }
                    ?>
                </div>
            </div>

            <div class="row buyer">
                <div class="col-md-12">
                    <div class="card-title">
                        <i class="fa fa-user"></i>Thông tin người mua hàng
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $form->field($shippingForm, 'buyer_name')->textInput(['placeholder' => Yii::t('frontend', 'Nhập họ tên của bạn')])->label('Họ tên*');
                            echo $form->field($shippingForm, 'buyer_phone')->textInput(['placeholder' => Yii::t('frontend', 'Nhập số điện thoại liên hệ')])->label('Số điện thoại*');
                            echo $form->field($shippingForm, 'buyer_email')->textInput(['placeholder' => Yii::t('frontend', 'Nhập địa chỉ email để nhận thông báo')])->label('Email nhận hành trình đơn hàng*');
                            //                            if($shippingForm->getStoreManager()->store->country_code === 'ID'){
                            echo $form->field($shippingForm, 'buyer_address')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            //                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            //if($shippingForm->getStoreManager()->store->country_code === 'ID'){
                            echo $form->field($shippingForm, 'buyer_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            //                            }else {
                            //                                echo $form->field($shippingForm, 'buyer_address')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            //                            }
                            echo $form->field($shippingForm, 'buyer_province_id')->widget(Select2::className(), [
                                'data' => $provinces,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => Yii::t('frontend', 'Choose the province'),
                                ],
                            ]);

                            echo Html::hiddenInput('hiddenBuyerDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenBuyerDistrictId']);

                            echo $form->field($shippingForm, 'buyer_district_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => [
                                    'pluginOptions' => ['allowClear' => true], 'pluginEvents' => [
                                        "change" => "function(event) { event.preventDefault();ws.payment.calculatorShipping(); }",
                                    ]
                                ],
                                'pluginOptions' => [
                                    'depends' => [Html::getInputId($shippingForm, 'buyer_province_id')],
                                    'placeholder' => Yii::t('frontend', 'Choose the district'),
                                    'url' => Url::toRoute(['sub-district']),
                                    'loadingText' => Yii::t('frontend', 'Loading district ...'),
                                    'initialize' => true,
                                    'params' => ['hiddenBuyerDistrictId']
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo $form->field($shippingForm, 'other_receiver')->checkbox()->label('Information of the receiver other than the buyer');
                    ?>
                </div>
            </div>

            <div class="row receiver">
                <div class="col-md-12">
                    <div class="card-title">
                        <i class="fa fa-user"></i>Thông tin người mua hàng
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $form->field($shippingForm, 'receiver_name')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            echo $form->field($shippingForm, 'receiver_phone')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            echo $form->field($shippingForm, 'receiver_address')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            //                            if($shippingForm->getStoreManager()->store->country_code === 'ID'){
                            echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);
                            //                            }
                            echo $form->field($shippingForm, 'receiver_province_id')->widget(Select2::className(), [
                                'data' => $provinces,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => Yii::t('frontend', 'Choose the province'),
                                ],
                            ]);

                            echo Html::hiddenInput('hiddenReceiverDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenReceiverDistrictId']);

                            echo $form->field($shippingForm, 'receiver_district_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => [
                                    'pluginOptions' => ['allowClear' => true], 'pluginEvents' => [
                                        "change" => "function(event) { event.preventDefault();ws.payment.calculatorShipping(); }",
                                    ]
                                ],
                                'pluginOptions' => [
                                    'depends' => [Html::getInputId($shippingForm, 'buyer_province_id')],
                                    'placeholder' => Yii::t('frontend', 'Choose the district'),
                                    'url' => Url::toRoute(['sub-district']),
                                    'loadingText' => Yii::t('frontend', 'Loading district ...'),
                                    'initialize' => true,
                                    'params' => ['hiddenBuyerDistrictId']
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 p-0">
                <?php
                echo Html::button('<i class="ico-location"></i> Thêm địa chỉ mới', ['class' => 'btn btn-default btn-shipping']);
                echo Html::button('LƯU ĐỊA CHỈ NÀY', ['class' => 'btn btn-default btn-save-shipping']);
                ?>
            </div>
            <?php

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li data-href=".step1"><i>1</i><span><?= Yii::t('frontend', 'Login'); ?></span></li>
        <li data-href=".step2"><i>2</i><span><?= Yii::t('frontend', 'Shipping address'); ?></span></li>
        <li data-href=".step3"><i>3</i><span><?= Yii::t('frontend', 'Payment'); ?></span></li>
    </ul>
    <div class="step-content row">
        <div class="col-md-8">
            <div class="step1">
                <?php echo $this->render('step/step1', []) ?>
            </div>
            <div class="step2 shipping-form">
                <!--                --><?php //echo $this->render('step/step2', [
                //                    'shippingForm' => $shippingForm,
                //                    'provinces' => $provinces
                //                ]) ?>
            </div>
            <div class="step3 payment-form">
                <!--                --><?php //echo $this->render('step/step3', [
                //                    'payment' => $payment,
                //                ]) ?>
            </div>
        </div>
        <div class="col-md-4">
            <!--            --><?php //echo $this->render('cart', ['payment' => $payment]) ?>
        </div>
    </div>
</div>