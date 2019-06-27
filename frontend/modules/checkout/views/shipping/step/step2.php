<?php

use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\models\ShippingForm $shippingForm */
/* @var array $provinces */
/* @var frontend\modules\payment\Payment $payment */

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
<div class="title"><?= Yii::t('frontend', 'Buyer and receiver information') ?></div>
<div class="payment-box">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'class' => 'payment-form'
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
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
    ])->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);

    echo $form->field($shippingForm, 'buyer_phone', [
        'template' => '<i class="icon phone"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Phone number')]);

    echo $form->field($shippingForm, 'buyer_email', [
        'template' => '<i class="icon email"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Email')]);

    echo $form->field($shippingForm, 'buyer_province_id', [
        'template' => '<i class="icon globe"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->widget(Select2::className(), [
        'data' => $provinces,
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => Yii::t('frontend', 'Choose the province'),
        ],
    ]);

    echo Html::hiddenInput('hiddenBuyerDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenBuyerDistrictId']);

    echo $form->field($shippingForm, 'buyer_district_id', [
        'template' => '<i class="icon city"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->widget(DepDrop::classname(), [
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
    ]);
    echo $form->field($shippingForm, 'buyer_address', [
        'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Address')]);

    echo $form->field($shippingForm, 'note_by_customer', [
//                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textarea(['rows' => 3, 'placeholder' => Yii::t('frontend', 'Note')]);

    echo Html::endTag('div');

    echo $form->field($shippingForm, 'other_receiver', [
        'template' => '{input}{label}',
        'options' => [
            'class' => 'check-info',
            'style' => 'margin-bottom: 1rem;'
        ]
    ])->checkbox()->label(Yii::t('frontend', 'Information of the receiver other than the buyer'));

    echo Html::beginTag('div', ['class' => 'receiver-form']);

    echo $form->field($shippingForm, 'receiver_name', [
        'template' => '<i class="icon user"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Full name')]);

    echo $form->field($shippingForm, 'receiver_phone', [
        'template' => '<i class="icon phone"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Phone number')]);

    echo $form->field($shippingForm, 'receiver_email', [
        'template' => '<i class="icon email"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Email')]);

    echo $form->field($shippingForm, 'receiver_province_id', [
        'template' => '<i class="icon city"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->dropDownList($provinces, [
        'prompt' => Yii::t('frontend', 'Choose the province'),
    ]);;

    echo Html::hiddenInput('hiddenReceiverDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenReceiverDistrictId']);

    echo $form->field($shippingForm, 'receiver_district_id', [
        'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->widget(DepDrop::classname(), [
        'pluginOptions' => [
            'depends' => [Html::getInputId($shippingForm, 'receiver_province_id')],
            'placeholder' => Yii::t('frontend', 'Choose the district'),
            'url' => Url::toRoute(['sub-district']),
            'loadingText' => Yii::t('frontend', 'Loading district ...'),
            'initialize' => true,
            'params' => ['hiddenReceiverDistrictId']
        ]
    ]);
    echo $form->field($shippingForm, 'receiver_address', [
        'template' => '<i class="icon mapmaker"></i>{input}{hint}{error}',
        'options' => ['class' => 'form-group']
    ])->textInput(['placeholder' => Yii::t('frontend', 'Address')]);

    echo Html::endTag('div');

    echo Html::submitButton(Yii::t('frontend', 'Choose payment method'), ['class' => 'btn btn-payment btn-block', 'id' => 'btnNextStep3']);
    ActiveForm::end();
    ?>
</div>
