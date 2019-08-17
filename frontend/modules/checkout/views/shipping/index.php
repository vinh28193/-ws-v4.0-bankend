<?php

use common\models\Address;
use common\models\db\TargetAdditionalFee;
use common\models\SystemDistrict;
use common\models\SystemZipcode;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use frontend\modules\payment\models\ShippingForm;
use yii\helpers\Inflector;
use frontend\modules\payment\Payment;

/* @var yii\web\View $this */
/* @var Payment $payment */
/* @var ShippingForm $shippingForm */
/* @var array $provinces */

$isID = $shippingForm->getStoreManager()->store->country_code === 'ID';

$showStep = true;
$activeStep = 2;
$otherReceiver = Html::getInputId($shippingForm, 'other_receiver');
$itemList['id'] = array();
$itemList['value'] = 0;
$itemListProduct = [];
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
    
     ws.initEventHandler('shipping-form','changeZipCode','keyup','#shippingform-buyer_post_code, #shippingform-receiver_post_code',function(event) {
          event.preventDefault();
          ws.payment.calculatorShipping();
     });
     
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
    

JS;
$this->registerJs($js);

$urlAjaxUrl = Url::toRoute(['/data/get-zip-code']);
$readyIs = <<<JS
 $(document).on('beforeSubmit', 'beforeSubmit', function (event, messages, deferreds) {
     console.log('beforeSubmit');
 });
JS;
$this->registerJs($readyIs,\yii\web\View::POS_READY);
$zipJs = <<<JS

    var suggestAddress = function(event) {
    
        var params = event.params;
        var data = params.data;
        var type = params._type;
        var target = $(event.target).data('ownwer');
        var province = $('#shippingform-'+target+'_province_id');
        province.prop('disabled',true);
        if(data.province === false){
            province.prop('disabled',false);
            data.province = '';
        }
        
        wsAddress.select2ChangeSelect(province,data.province);
        var depdropDistrict = $('#shippingform-'+target+'_district_id');
        
        depdropDistrict.trigger('change');
        
        depdropDistrict.on('depdrop:afterChange', function(event, id, value, jqXHR, textStatus) {
            depdropDistrict.attr('disabled','disabled');
            if(data.district === false){
                depdropDistrict.removeAttr('disabled');
            }
           wsAddress.select2ChangeSelect(depdropDistrict,data.district);
        });
    };
JS;
$this->registerJs($zipJs, yii\web\View::POS_HEAD);
?>
<style type="text/css">

</style>

<?php if ($shippingForm->getUser() === null && $payment->page === Payment::PAGE_CHECKOUT): ?>
    <div class="card card-checkout card-information">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-information">
                        <?= Yii::t('frontend', '<a href="{loginUrl}"> Login / sign up </a> now for more convenience & incentives', [
                            'loginUrl' => Yii::$app->user->loginUrl
                        ]); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($payment->page === Payment::PAGE_ADDITION): ?>
    <?php /** @var $order \common\models\Order */ ?>
    <?php if (($order = array_values($payment->getOrders())[0]) !== null): ?>
        <div class="card card-checkout card-addition">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row buyer-address">
                            <div class="col-md-12">
                                <div class="card-title">
                                    <i class="fa fa-user"></i> <?php echo Yii::t('frontend', 'Buyer information'); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Name : {name}', ['name' => $order->buyer_name]); ?>
                                </p>
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Contact : {contact}', ['contact' => implode('/', [$order->buyer_phone, $order->buyer_email])]); ?>
                                </p>
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Address : {address}', ['address' => implode(' - ', [$order->buyer_address, $order->buyer_district_name, $order->buyer_province_name])]); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row buyer-address">
                            <div class="col-md-12">
                                <div class="card-title">
                                    <i class="fa fa-user"></i><?php echo Yii::t('frontend', 'Shipping information'); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Address : {address}', ['address' => implode(' - ', [$order->receiver_name, $order->receiver_phone, $order->receiver_address, $order->receiver_district_name, $order->buyer_province_name])]); ?>
                                </p>
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Courier : {courier}', ['courier' => $order->courier_name]); ?>
                                </p>
                                <p class="mb-1">
                                    <?php echo Yii::t('frontend', 'Courier estimated time: {time} days', ['time' => implode('-', explode(' ', $order->courier_delivery_time))]); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php foreach ($payment->getOrders() as $order): ?>
        <?php
        $refKey = $payment->page === Payment::PAGE_CHECKOUT ? $order->cartId : $order->ordercode;
        $remainingAmount = $order->total_final_amount_local - $order->total_paid_amount_local;
        ?>
        <div class="card card-checkout card-addition"
             data-key="<?= $refKey; ?>">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-title seller">
                            <?php
                            $seller = $order->seller;
                            echo Yii::t('frontend', 'Order {code} sold by: {store} on {portal} ({count}) items', [
                                'code' => Html::tag('span', $order->ordercode, ['class' => 'text-danger']),
                                'store' => Html::tag('span', $seller->seller_name, ['style' => 'color: #2b96b6;']),
                                'portal' => strtoupper($seller->portal) === 'EBAY' ? 'eBay' : Inflector::camelize(strtolower($seller->portal)),
                                'count' => count($order->products)
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="product-header row pt-2">
                    <div class="col-md-1 text-right"></div>
                    <div class="col-md-9 text-left"><?= Yii::t('frontend', 'Product name'); ?></div>
                    <div class="col-md-2 text-right"><?= Yii::t('frontend', 'Quantity'); ?></div>
                </div>
                <div class="row product-list">
                    <?php foreach ($order->products as $product): ?>

                        <div class="col-md-12 product-item">
                            <div class="row product">
                                <div class="col-md-1 pt-2">
                                    <img src="<?= $product->link_img; ?>"
                                         alt="<?= $product->product_name; ?>" width="80%" height="100px"
                                         title="<?= $product->product_name; ?>">
                                </div>
                                <div class="col-md-9 text-left pt-4">
                                    <?= $product->product_name; ?>
                                </div>
                                <div class="col-md-2 text-right pt-4">x <?php echo $product->quantity_customer; ?></div>
                            </div>


                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row additional-fee">
                    <div class="col-md-6 col-sm-12"></div>
                    <div class="col-md-6 col-sm-12">
                        <div class="additional-list">
                            <table class="table table-borderless table-fee">

                                <tr>
                                    <td class="header"><?= Yii::t('frontend', 'Payment transaction'); ?></td>
                                    <td class="text-right"><?= $order->payment_transaction_code ?></td>
                                </tr>
                                <tr>
                                    <td class="header"><?= Yii::t('frontend', 'Payment Amount'); ?></td>
                                    <td class="value text-danger"><?= $storeManager->showMoney($order->total_final_amount_local); ?></td>
                                </tr>
                                <tr>
                                    <td class="header"><?= Yii::t('frontend', 'Payment paid amount'); ?></td>
                                    <td class="value text-danger"><?= $storeManager->showMoney($order->total_paid_amount_local); ?></td>
                                </tr>
                                <tr>
                                    <td class="header"><?= Yii::t('frontend', 'Remaining amount'); ?></td>
                                    <td class="value text-danger"><?= $storeManager->showMoney($remainingAmount); ?></td>
                                </tr>
                                <tr>
                                    <td class="header"><?= Yii::t('frontend', 'Request payment additional amount'); ?></td>
                                    <td class="value text-danger"><?= $storeManager->showMoney($order->getTotalFinalAmount()); ?></td>
                                </tr>
                                <tr style="font-weight: 700;border-top: 1px solid #efefef">
                                    <td class="header"><?= Yii::t('frontend', 'You must payment') ?></td>
                                    <td class="value text-danger"
                                        data-origin="<?= $order->getTotalFinalAmount() ?>"><?= $storeManager->showMoney($order->getTotalFinalAmount()); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>

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
                'validateOnSubmit' => false,
                'validateOnChange' => true,
                'validateOnBlur' => false,
                'validateOnType' => true,
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
                            if ($isID) {
//                            echo $form->field($shippingForm, 'buyer_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                                echo $form->field($shippingForm, 'buyer_post_code')->widget(Select2::className(), [
                                    'options' => [
                                        'data-ownwer' => 'buyer',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 4,
                                        'placeholder' => Yii::t('frontend', 'Enter your post code'),
                                        'ajax' => [
                                            'url' => $urlAjaxUrl,
                                            'dataType' => 'json',
                                            'data' => new JsExpression('wsAddress.zipAjaxParam'),
                                            'processResults' => new JsExpression('wsAddress.zipAjaxProcessResults'),
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                        'templateResult' => new JsExpression('wsAddress.formatZipAjaxResults'),
                                        'templateSelection' => new JsExpression('wsAddress.formatZipAjaxSelection'),

                                    ],
                                    'pluginEvents' => [
                                        "select2:select" => new JsExpression('suggestAddress'),
                                        "select2:unselect" => new JsExpression('suggestAddress')
                                    ]
                                ])->label(Yii::t('frontend', 'Post Code'));
                            } else {
                                echo $form->field($shippingForm, 'buyer_address')->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')])->label(Yii::t('frontend', 'Address'));
                            }
                            echo $form->field($shippingForm, 'buyer_province_id')->widget(Select2::className(), [
                                'data' => $shippingForm->getProvinces(),
                                'disabled' => $isID,
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
                                    'initialize' => true,
                                    'url' => Url::toRoute(['sub-district']),
                                    'params' => ['hiddenBuyerDistrictId']
                                ],
//
                            ])->label(Yii::t('frontend', 'District')); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo $form->field($shippingForm, 'other_receiver')->checkbox()->label(Yii::t('frontend', 'Information of the receiver other than the buyer'));
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
//                            echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                                echo $form->field($shippingForm, 'receiver_post_code')->widget(Select2::className(), [
                                    'options' => [
                                        'data-ownwer' => 'receiver',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 4,
                                        'placeholder' => Yii::t('frontend', 'Enter your post code'),
                                        'ajax' => [
                                            'url' => $urlAjaxUrl,
                                            'dataType' => 'json',
                                            'data' => new JsExpression('wsAddress.zipAjaxParam'),
                                            'processResults' => new JsExpression('wsAddress.zipAjaxProcessResults'),
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                        'templateResult' => new JsExpression('wsAddress.formatZipAjaxResults'),
                                        'templateSelection' => new JsExpression('wsAddress.formatZipAjaxSelection'),

                                    ],
                                    'pluginEvents' => [
                                        "select2:select" => new JsExpression('suggestAddress'),
                                        "select2:unselect" => new JsExpression('suggestAddress')
                                    ]
                                ])->label(Yii::t('frontend', 'Post Code'));
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
        <?php $refKey = $payment->page === Payment::PAGE_CHECKOUT ? $order->cartId : $order->ordercode;?>
        <div class="card card-checkout card-order"
             data-key="<?= $refKey; ?>">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-title seller">
                            <?php
                            $seller = $order->seller;
                            echo Yii::t('frontend', '{portal} store: {store} ({count}) items', [
                                'portal' => strtoupper($seller->portal) === 'EBAY' ? 'eBay' : Inflector::camelize(strtolower($seller->portal)),
                                'store' => Html::tag('span', $seller->seller_name, ['style' => 'color: #2b96b6;']),
                                'count' => count($order->products)
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="product-header row pt-2">
                    <div class="col-md-1 text-right"></div>
                    <div class="col-md-3 text-left"><?= Yii::t('frontend', 'Product name'); ?></div>
                    <div class="col-md-1 text-right"><?= Yii::t('frontend', 'Price'); ?></div>
                    <div class="col-md-2 text-right"><?= Yii::t('frontend', 'Quantity'); ?></div>
                    <div class="col-md-2 text-right"><?= Yii::t('frontend', 'Tax/Domestic shipping'); ?></div>
                    <div class="col-md-1 text-right"><?= Yii::t('frontend', 'Purchase Fee'); ?></div>
                    <div class="col-md-2 text-right"><?= Yii::t('frontend', 'Total amount'); ?></div>

                </div>
                <div class="row product-list">
                    <?php foreach ($order->products as $product): ?>
                        <?php
                        $itemList['id'][] = $product->parent_sku;
                        $itemList['value'] += $product->total_final_amount_local;
                        $itemListProduct[] = [
                                'id' => $product->parent_sku,
                                'name' => $product->product_name,
                                'category' => $product->category_id,
                                'variant' => $product->sku,
                                'price' => $product->total_final_amount_local,
                                'position' => 0,
                        ];
                        $productFees = ArrayHelper::index($product->productFees, null, 'name');
                        $purchaseFee = 0;
                        $purchaseFee = isset($productFees['purchase_fee']) ? $productFees['purchase_fee'][0]->local_amount : $purchaseFee;
                        ?>

                        <div class="col-md-12 product-item">
                            <div class="row product">
                                <div class="col-md-1 pt-2">
                                    <img src="<?= $product->link_img; ?>"
                                         alt="<?= $product->product_name; ?>" width="80%" height="100px"
                                         title="<?= $product->product_name; ?>">
                                </div>
                                <div class="col-md-3 text-left pt-4">
                                    <?= $product->product_name; ?>
                                </div>
                                <div class="col-md-1 text-right pt-4">
                                    <?php echo $storeManager->showMoney($product->price_amount_local); ?>
                                </div>
                                <div class="col-md-2 text-right pt-4">x <?php echo $product->quantity_customer; ?></div>
                                <div class="col-md-2 text-right pt-4">
                                    <?php
                                    $usPrice = 0;
                                    foreach (['tax_fee', 'shipping_fee'] as $feeUs) {
                                        if (isset($productFees[$feeUs])) {
                                            foreach ($productFees[$feeUs] as $productFee) {
                                                /** @var $productFee common\models\db\TargetAdditionalFee */
                                                $usPrice += (int)$productFee->local_amount;
                                            }
                                        }
                                    }
                                    echo $storeManager->showMoney($usPrice);
                                    ?>
                                </div>
                                <div class="col-md-1 text-right pt-4">
                                    <?php
                                    echo $storeManager->showMoney($purchaseFee);
                                    ?>
                                </div>
                                <div class="col-md-2 text-right pt-4">
                                    <?php
                                    echo $storeManager->showMoney($product->total_final_amount_local + $purchaseFee);
                                    ?>
                                </div>
                            </div>


                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row additional-fee">
                    <div class="col-md-8 col-sm-12"></div>
                    <div class="col-md-4 col-sm-12">
                        <div class="additional-list">
                            <!--                        <div class="dropdown courier-dropdown">-->
                            <!--                            <button class="btn btn-secondary dropdown-toggle" type="button"-->
                            <!--                                    id="courierDropdownButton" data-toggle="dropdown" aria-haspopup="true"-->
                            <!--                                    aria-expanded="false">-->
                            <!--                                    <span class="text">-->
                            <!--                                        <p style="margin-bottom: 0">Choose shipping service</p>-->
                            <!--                                        <p class="courier-name" style="margin-bottom: 0">No shipping service</p>-->
                            <!--                                    </span>-->
                            <!--                            </button>-->
                            <!--                            <div class="dropdown-menu" aria-labelledby="courierDropdownButton" id="courierDropdownMenu">-->
                            <!--                                <span class="dropdown-item">-->
                            <!--                                     Boxme International Express (12-15 day )-->
                            <!--                                </span>-->
                            <!--                                <span class="dropdown-item">-->
                            <!--                                    Boxme International Express (12-15 day )-->
                            <!--                                </span>-->
                            <!--                            </div>-->
                            <!--                        </div>-->
                            <table class="table table-borderless table-fee">
                                <tr data-role="fee" data-fee="international_shipping_fee">
                                    <td class="header"><?= Yii::t('frontend', 'Shipping fee'); ?>
                                        <?php
                                        $tooltipMessage = Yii::t('frontend', 'for {weight} {dram}', [
                                            'weight' => $order->total_weight_temporary,
                                            'dram' => 'kg'
                                        ])
                                        ?>
                                        <i class="la la-question-circle code-info" data-toggle="tooltip"
                                           data-placement="top" title="<?= $tooltipMessage; ?>"
                                           data-original-title="<?= $tooltipMessage; ?>"></i>
                                    </td>
                                    <td class="value"><?= $storeManager->showMoney($order->getAdditionalFees()->getTotalAdditionalFees('international_shipping_fee')[1]); ?></td>
                                </tr>
                                <tr class="courier">
                                    <td class="header"><?= Yii::t('frontend', 'Estimated time') ?></td>
                                    <td class="text-right">
                                        <?php echo Yii::t('frontend', 'Please select your address to suggest') ?>
                                    </td>
                                </tr>
                                <tr class="discount-helper">
                                    <td colspan="2" class="text-right">
                                        <?php echo Yii::t('frontend', 'If you have a discount code, {handle}', [
                                            'handle' => Html::a(Yii::t('frontend', ' enter the code'), new JsExpression('javascript:void(0)'), ['style' => 'color: #2b96b6;', 'onclick' => 'ws.payment.enableCoupon(\'' . $refKey . '\')'])
                                        ]) ?>
                                    </td>
                                </tr>
                                <tr class="discount-detail" style="display: none">
                                    <td class="header">
                                        <?= Yii::t('frontend', 'Coupon code'); ?> <span
                                                class="coupon-code"></span>
                                    </td>
                                    <td class="value">
                                        <div class="input-group discount-input" style="margin-bottom: 1rem;">
                                            <input type="text" class="form-control" name="couponCode">
                                            <div class="input-group-append">
                                                <button data-key="<?php echo $refKey; ?>"
                                                        class="btn btn-outline-secondary" type="button"
                                                        id="applyCouponCode"><?php echo Yii::t('frontend', 'Apply'); ?></button>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="discountErrors" style="display: none"></span>
                                        <span class="discount-amount" style="display: none"><span
                                                    class="discount-value"></span>
                                            <i class="la la-times text-danger del-coupon"
                                               onclick="ws.payment.removeCouponCode('<?= $refKey; ?>')"></i></span>
                                    </td>
                                </tr>
                                <tr class="discount" style="display: none">
                                    <td><?= Yii::t('frontend', 'Discount amount'); ?></td>
                                    <td><?= $storeManager->showMoney($order->discountAmount); ?></td>
                                </tr>
                                <tr class="final-amount">
                                    <td class="header"><?= Yii::t('frontend', 'Amount must to pre-pay') ?></td>
                                    <td class="value text-danger"
                                        data-origin="<?= $order->getTotalFinalAmount() ?>"><?= $storeManager->showMoney($order->getTotalFinalAmount()); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="card card-checkout card-information" style="margin-top: -1rem">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-information">
                        <?= Yii::t('frontend', 'Prices of items, seller \'s domestic shipping fees and initial taxes of countries may not be accurate. You will have to pay extra if necessary by the balance in your wallet or other forms of payment. Whenever items or packages arrive at the warehouse and their weight or size is updated by the warehouse manager {name}, the additional invoice will be sent to you.', [
                            'name' => $storeManager->store->name
                        ]); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
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
<?php
$idRemarketing = json_encode($itemList['id']);
$valueRemarketing = $itemList['value'];
?>
<script>
    var checkoutProducts = <?= json_encode($itemListProduct) ?>;
    window.addEventListener('load', function () {
        try {
            ga('set', 'dimension1', <?= $idRemarketing ?>); // Please make sure that Dimension 1 is set as the Custom Dimension for Product ID
        } catch (e) {
        }
        try {
            ga('set', 'dimension2', 'conversionintent'); // Please make sure that Dimension 2 is set as the Custom Dimension for Page Type
        } catch (e) {
        }
        try {
            ga('set', 'dimension3', <?= $valueRemarketing ?>); // Please make sure that Dimension 3 is set as the Custom Dimension for Total Value
        } catch (e) {
        }
        ga('send', 'event', 'page', 'visit', 'conversionintent', {
            'nonInteraction': 1
        });
        $.each(checkoutEcommerces.products,function (index,product) {
            ga('ec:addProduct', {
                'id': product.id,
                'name': product.name,
                'category': product.category,
                'variant': product.variant,
                'price': product.price,
                'quantity': product.quantity,
                'position': product.position
            });
        });
        ga('ec:setAction','checkout', {
            'step': 2
        });
        ga('send', 'pageview');

    });
</script>

