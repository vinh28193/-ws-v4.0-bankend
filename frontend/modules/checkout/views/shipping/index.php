<?php

use common\models\Address;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\modules\payment\models\ShippingForm;

/* @var yii\web\View $this */
/* @var integer $activeStep */
/* @var frontend\modules\payment\Payment $payment */
/* @var ShippingForm $shippingForm */
/* @var array $provinces */


$showStep = true;
$activeStep = 2;
$otherReceiver = Html::getInputId($shippingForm, 'other_receiver');
$js = <<< JS
    
    var showReceiver = function(element) {
        element  = $(element);
        var checked = element.is(':checked');
        var target = $('div.receiver-address');
        if(target.hasClass('close')){
             target.removeClass('close');
             target.addClass('open');
        }else if(target.hasClass('open')){
            target.removeClass('open');
            target.addClass('close');
        }else {
            target.removeClass('close');
            target.removeClass('open');
            target.addClass( checked? 'open' : 'close');
        }
        if(checked === false){
            $('#shippingform-enable_receiver').val(0);
        }
        ws.payment.calculatorShipping();
        ws.initEventHandler('shipping-form','calculatorShipping','change','input[type=radio]',function(event) {
            ws.payment.calculatorShipping();
        });
    };
    var otherReceiver =  $('#$otherReceiver');
    // showReceiver(otherReceiver);
    ws.initEventHandler('shipping-form','change','change','#$otherReceiver',function(event) {
        event.preventDefault();
        showReceiver(this);
    });
    
     
    ws.initEventHandler('shipping-form','addNewAddress','click','button.btn-shipping',function(event) {
        event.preventDefault();
        var btn = $(this);
        showNewAddress(btn.data('ref'),btn.data('enable'));
    });
    
    var showNewAddress = function(type,close = 1){
        if(!type){
            return;
        }
        close = Number(close) || 1;
        var findEnable = 'input#shippingform-enable_'+type;
        var enable = $(findEnable);
        enable.attr('value',close);
        var listElement = $('div.'+type +'-list');
        
        if(listElement.hasClass(close === 1 ? 'open' :'close')){
            listElement.removeClass(close === 1 ? 'open' :'close')
        }
        listElement.addClass(close === 1 ? 'close' :'open');
        
        var newElement =  $('div.'+type +'-new');
        if(newElement.hasClass(close === 1 ? 'close' :'open')){
            newElement.removeClass(close === 1 ? 'close' :'open')
        }
        newElement.addClass(close === 1 ? 'open' :'close');
        ws.payment.calculatorShipping();
    };
    
    $(document).on("beforeSubmit", "form.payment-form", function (e) {
        e.preventDefault();
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) 
        {
            return false;
        }
        window.scrollTo(0, 0);
        // send data to actionSave by ajax request.
        return false; // Cancel form submitting.
    });
JS;
$this->registerJs($js);
?>
<style type="text/css">
    .btn-payment{
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        border-radius: 3px;
        border: 1px solid #d25e0d;
        background-image: linear-gradient(180deg, #ff9d17 0%, #e67424 100%);
    }
</style>
<div class="container">
    <div class="card card-checkout card-shipping">
        <div class="card-body">
            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'id' => 'shippingForm',
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
            echo Html::activeHiddenInput($shippingForm, 'customer_id');
            echo Html::activeHiddenInput($shippingForm, 'enable_buyer');
            echo Html::activeHiddenInput($shippingForm, 'enable_receiver');
            $buyerAddress = $shippingForm->getBuyerAddress();
            $receiverAddress = $shippingForm->getReceiverAddress();
            ?>

            <div class="row buyer-address">
                <div class="col-md-12">
                    <div class="card-title">
                        <i class="fa fa-user"></i> <?php echo Yii::t('frontend', 'Buyer information'); ?>
                    </div>
                </div>
                <div class="col-md-12 buyer-list <?= ($buyerAddress !== null && $shippingForm->enable_buyer === ShippingForm::NO) ? 'open' : 'close'; ?>">
                    <?php
                    if ($buyerAddress !== null) {
                        echo 'Name:' . $buyerAddress->first_name . '' . $buyerAddress->last_name;
                        echo $form->field($shippingForm, 'buyer_address_id')->hiddenInput()->label(false);
                        if ($shippingForm->enable_buyer === ShippingForm::NO) {
                            $this->registerJs('ws.payment.calculatorShipping();');
                        }
                    }else {
                        $shippingForm->enable_buyer = ShippingForm::YES;
                    }
                    ?>
                </div>
                <div class="col-md-12 buyer-new <?= $shippingForm->enable_buyer === ShippingForm::NO ? 'close' : 'open'; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $form->field($shippingForm, 'buyer_name')->textInput(['placeholder' => Yii::t('frontend', 'Enter your full name')])->label(Yii::t('frontend', 'Full Name'));
                            echo $form->field($shippingForm, 'buyer_phone')->textInput(['placeholder' => Yii::t('frontend', 'Enter your phone number')])->label(Yii::t('frontend', 'Phone'));
                            echo $form->field($shippingForm, 'buyer_email')->textInput(['placeholder' => Yii::t('frontend', 'Enter your email address to receive notifications')])->label(Yii::t('frontend', 'Email To Receiver Tracking'));
                            if ($shippingForm->getStoreManager()->store->country_code === 'ID') {
                                echo $form->field($shippingForm, 'buyer_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label('Address');
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($shippingForm->getStoreManager()->store->country_code === 'ID') {
                                echo $form->field($shippingForm, 'buyer_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                            } else {
                                echo $form->field($shippingForm, 'buyer_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label(Yii::t('frontend', 'Address'));
                            }
                            echo $form->field($shippingForm, 'buyer_province_id')->widget(Select2::className(), [
                                'data' => $shippingForm->getProvinces(),
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => Yii::t('frontend', 'Choose the province'),
                                ],
                            ])->label(Yii::t('frontend', 'Province'));

                            echo Html::hiddenInput('hiddenBuyerDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenBuyerDistrictId']);

                            echo $form->field($shippingForm, 'buyer_district_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => [
                                    'pluginOptions' => ['allowClear' => true], 'pluginEvents' => [
                                        "select2:select" => "function(event) {ws.payment.calculatorShipping(); }",
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
                            ])->label(Yii::t('frontend', 'District')); ?>
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

            <div class="row receiver-address <?= $shippingForm->other_receiver === ShippingForm::YES ? 'open' : 'close'; ?>">
                <div class="col-md-12">
                    <div class="card-title">
                        <i class="fa fa-user"></i><?php echo Yii::t('frontend', 'Receiver information'); ?>
                    </div>
                </div>
                <div class="col-md-12 receiver-list <?= $shippingForm->enable_receiver === ShippingForm::NO ? 'open' : 'close'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $receiverAddress = ArrayHelper::map($receiverAddress, 'id', function ($shipping) {
                                /** @var $address Address */
                                return implode(' ', [$shipping->first_name, $shipping->last_name]);
                            });
                            echo $form->field($shippingForm, 'receiver_address_id')->radioList($receiverAddress)->label(false);
                            ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo Html::button('<i class="ico-location"></i>' . Yii::t('frontend', 'Add new receiver address'), ['class' => 'btn btn-default btn-shipping', 'data-ref' => 'receiver', 'data-enable' => ShippingForm::YES]); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 receiver-new <?= $shippingForm->enable_receiver === ShippingForm::NO ? 'close' : 'open'; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $form->field($shippingForm, 'receiver_name')->textInput(['placeholder' => Yii::t('frontend', 'Enter your full name')])->label(Yii::t('frontend', 'Full Name'));
                            echo $form->field($shippingForm, 'receiver_phone')->textInput(['placeholder' => Yii::t('frontend', 'Enter the contact phone number')])->label(Yii::t('frontend', 'Phone'));
                            echo $form->field($shippingForm, 'receiver_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label(Yii::t('frontend', 'Address'));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($shippingForm->getStoreManager()->store->country_code === 'ID') {
                                echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                            }
                            echo $form->field($shippingForm, 'receiver_province_id')->widget(Select2::className(), [
                                'data' => $shippingForm->getProvinces(),
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => Yii::t('frontend', 'Choose the province'),
                                ],
                            ])->label(Yii::t('frontend', 'Province'));

                            echo Html::hiddenInput('hiddenReceiverDistrictId', $shippingForm->buyer_district_id, ['id' => 'hiddenReceiverDistrictId']);

                            echo $form->field($shippingForm, 'receiver_district_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => [
                                    'pluginOptions' => ['allowClear' => true],
                                    'pluginEvents' => [
                                        "select2:select" => "function(event) { ;ws.payment.calculatorShipping(); }",
                                    ]
                                ],
                                'pluginOptions' => [
                                    'depends' => [Html::getInputId($shippingForm, 'receiver_province_id')],
                                    'placeholder' => Yii::t('frontend', 'Choose the district'),
                                    'url' => Url::toRoute(['sub-district']),
                                    'loadingText' => Yii::t('frontend', 'Loading district ...'),
                                    'initialize' => true,
                                    'params' => ['hiddenReceiverDistrictId']
                                ],
                            ])->label(Yii::t('frontend', 'District'));

                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($shippingForm->getUser() !== null) {
                                echo $form->field($shippingForm, 'save_receiver_address')->checkbox()->label(Yii::t('frontend', 'Save this shipping address for the next time'));
                                if (!\common\helpers\WeshopHelper::isEmpty($shippingForm->getReceiverAddress())) {
                                    echo Html::button('<i class="ico-location"></i>' . Yii::t('frontend', 'Use exists receiver address'), ['class' => 'btn btn-default btn-shipping', 'data-ref' => 'receiver', 'data-enable' => ShippingForm::NO]);
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <?php

            ActiveForm::end();
            ?>
        </div>
    </div>
    <?php foreach ($payment->getOrders() as $order): ?>
        <div class="card card-checkout card-order" data-key="<?= $order->cartId ?>">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-title seller">
                            <?php
                            $seller = $order->seller;
                            ?>
                            <?= $seller->portal; ?> store: <a
                                    href="<?= $seller->seller_link_store !== null ? $seller->seller_link_store : '#'; ?>"><?= $seller->seller_name; ?></a>
                            (<?= count($order->products); ?> items)
                            <!--                        <i class="fa fa-user"></i>--><?php //echo Yii::t('frontend', 'Amount needed to prepay'); ?>
                        </div>
                    </div>
                </div>
                <div class="row product-list">
                    <?php foreach ($order->products as $product): ?>
                        <div class="col-md-12 product-item">
                            <div class="row product">
                                <div class="col-md-2 img-responsive">
                                    <img style="width: 100%" src="<?= $product->link_img ?>"
                                         alt="<?= $product->product_name; ?>">
                                </div>
                                <div class="col-md-8">
                                    <p><?= $product->product_name; ?></p>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-danger">
                                        <?php echo $storeManager->showMoney($product->total_price_amount_local); ?>
                                    </span>
                                </div>
                            </div>


                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row additional-fee">
                    <div class="col-md-4">
                        <div class="additional-list">
                            <div class="dropdown courier-dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="courierDropdownButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    <span class="text">
                                        <p style="margin-bottom: 0">Choose shipping service</p>
                                        <p class="courier-name" style="margin-bottom: 0">No shipping service</p>
                                    </span>
                                </button>
                                <!--                                <div class="dropdown-menu" aria-labelledby="courierDropdownButton"-->
                                <!--                                     id="courierDropdownMenu">-->
                                <!--                                    <a class="dropdown-item" href="#">Action</a>-->
                                <!--                                    <a class="dropdown-item" href="#">Another action</a>-->
                                <!--                                    <a class="dropdown-item" href="#">Something else here</a>-->
                                <!--                                </div>-->
                            </div>
                            <table class="table table-borderless table-fee">
                                <tr>
                                    <th class="fee-header"><?= Yii::t('frontend', 'Total Order'); ?></th>
                                    <td class="fee-value"><?= $storeManager->showMoney($order->total_amount_local); ?></td>
                                </tr>
                                <tr data-role="fee" data-fee="purchase_fee">
                                    <th class="fee-header"><?= Yii::t('frontend', 'Purchase Fee'); ?></th>
                                    <td class="fee-value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('purchase_fee')[1]); ?></td>
                                </tr>
                                <tr data-role="fee" data-fee="tax_fee">
                                    <th class="fee-header"><?= Yii::t('frontend', 'Us Tax'); ?></th>
                                    <td class="fee-value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('tax_fee')[1]); ?></td>
                                </tr>
                                <tr data-role="fee" data-fee="international_shipping_fee">
                                    <th class="fee-header"><?= Yii::t('frontend', 'Temporary Shipping Fee (for {weight} kg)', ['weight' => $order->total_weight_temporary]); ?></th>
                                    <td class="fee-value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee')[1]); ?></td>
                                </tr>
                                <tr class="final-amount">
                                    <th class="fee-header"><?= Yii::t('frontend', 'Amount needed to prepay') ?></th>
                                    <td class="fee-value"
                                        data-origin="<?= $order->total_final_amount_local; ?>"><?= $storeManager->showMoney($order->total_final_amount_local); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="card card-checkout card-payment">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title seller">
                        <i class="fa fa-user"></i><?php echo Yii::t('frontend', 'Select a payment method'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $payment->initPaymentView(); ?>
                </div>
            </div>
        </div>
    </div>
</div>



