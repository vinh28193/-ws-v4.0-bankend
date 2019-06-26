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

</style>
<div class="container">
    <?php if ($shippingForm->getUser() === null): ?>
        <div class="card card-information">
            <div class="card-body">
                <?= Yii::t('frontend', '<a href="{loginUrl}"> Login / sign up </a> now for more convenience & incentives', [
                    'loginUrl' => Yii::$app->user->loginUrl
                ]); ?>
            </div>
        </div>
    <?php endif; ?>
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
                    } else {
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
                            echo $form->field($shippingForm, 'buyer_email')->textInput(['placeholder' => Yii::t('frontend', 'Enter your email address to receive notifications'),])->label(Yii::t('frontend', 'Email To Receiver Tracking'));
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
                <?php
                ?>
                <div class="col-md-12 receiver-list <?= !empty($receiverAddress) && $shippingForm->enable_receiver === ShippingForm::NO ? 'open' : 'close'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (!empty($receiverAddress)) {
                                $receiverAddress = ArrayHelper::map($receiverAddress, 'id', function ($shipping) {
                                    /** @var $address Address */
                                    return implode(' ', [$shipping->first_name, $shipping->last_name]);
                                });
                                echo $form->field($shippingForm, 'receiver_address_id')->radioList($receiverAddress)->label(false);
                            } else {
                                $shippingForm->enable_receiver = ShippingForm::YES;
                            }

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
                            if ($shippingForm->getStoreManager()->store->country_code === 'ID') {
                                echo $form->field($shippingForm, 'receiver_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label(Yii::t('frontend', 'Address'));
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($shippingForm->getStoreManager()->store->country_code === 'ID') {
                                echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                            } else {
                                echo $form->field($shippingForm, 'receiver_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label(Yii::t('frontend', 'Address'));
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
                        <?php
                        $productFees = ArrayHelper::index($product->productFees, null, 'name');
                        ?>
                        <div class="col-md-12 product-item">
                            <div class="row product">
                                <div class="col-md-1 img-responsive">
                                    <img style="width: 100%;max-height: 100px" src="<?= $product->link_img ?>"
                                         alt="<?= $product->product_name; ?>">
                                </div>
                                <div class="col-md-5" style="vertical-align: middle">
                                    <?= $product->product_name; ?>
                                </div>

                                <div class="col-md-2" style="vertical-align: middle">
                                    <?= $storeManager->showMoney($product->price_amount_local); ?>
                                </div>
                                <div class="col-md-1" style="vertical-align: middle">
                                    x<?= $product->quantity_customer; ?>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <span class="text-danger">
                                        <?php echo $storeManager->showMoney($product->total_price_amount_local); ?>
                                    </span>
                                </div>
                            </div>


                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row additional-fee">
                    <div class="col-md-6 col-sm-12"></div>
                    <div class="col-md-6 col-sm-12">
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
                                    <th class="header"><?= Yii::t('frontend', 'Total Order'); ?></th>
                                    <td class="value"><?= $storeManager->showMoney($order->total_amount_local); ?></td>
                                </tr>
                                <tr data-role="fee" data-fee="purchase_fee">
                                    <th class="header"><?= Yii::t('frontend', 'Purchase Fee'); ?></th>
                                    <td class="value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('purchase_fee')[1]); ?></td>
                                </tr>
                                <tr data-role="fee" data-fee="tax_fee">
                                    <th class="header"><?= Yii::t('frontend', 'Us Tax'); ?></th>
                                    <td class="value">
                                        <?php
                                        if (strtoupper($order->portal) === \common\products\BaseProduct::TYPE_AMAZON_US) {
                                            echo Yii::t('frontend', 'Fee');
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr data-role="fee" data-fee="international_shipping_fee">
                                    <th class="header"><?= Yii::t('frontend', 'Temporary Shipping Fee (for {weight} kg)', ['weight' => $order->total_weight_temporary]); ?></th>
                                    <td class="value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee')[1]); ?></td>
                                </tr>
                                <tr class="discount-detail">
                                    <th class="header"><?= Yii::t('frontend', 'Coupon code'); ?> <span
                                                class="coupon-code"></span>
                                    </th>
                                    <td class="value">
                                        <div class="input-group discount-input" style="margin-bottom: 1rem">
                                            <input type="text" class="form-control" name="couponCode">
                                            <div class="input-group-append">
                                                <button data-key="<?php echo $order->cartId; ?>"
                                                        class="btn btn-outline-secondary" type="button"
                                                        id="applyCouponCode"><?php echo Yii::t('frontend', 'Apply'); ?></button>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="discountErrors" style="display: none"></span>
                                        <span class="discount-amount" style="display: none"><span
                                                    class="discount-value"></span>
                                            <i class="la la-times text-danger del-coupon"
                                               onclick="ws.payment.removeCouponCode('<?= $order->cartId; ?>')"></i></span>
                                    </td>
                                </tr>
                                <tr class="discount" style="display: none">
                                    <th><?= Yii::t('frontend', 'Discount amount'); ?></th>
                                    <td><?= $storeManager->showMoney($order->discountAmount); ?></td>
                                </tr>
                                <tr class="final-amount">
                                    <th class="header"><?= Yii::t('frontend', 'Amount needed to prepay') ?></th>
                                    <td class="value"
                                        data-origin="<?= $order->getTotalFinalAmount() ?>"><?= $storeManager->showMoney($order->getTotalFinalAmount()); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="card card-information" style="margin-top: -1rem">
        <div class="card-body">
            <?= Yii::t('frontend', 'Prices of items, seller \'s domestic shipping fees and initial taxes of countries may not be accurate. You will have to pay extra if necessary by the balance in your wallet or other forms of payment. Whenever items or packages arrive at the warehouse and their weight or size is updated by the warehouse manager {name}, the additional invoice will be sent to you.', [
                'name' => $storeManager->store->name
            ]); ?>
        </div>
    </div>
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



