<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;
/**
 * @var $address \common\models\Address
 * @var $user \common\models\User
 */
?>
<?php $form = ActiveForm::begin([
    'action' => 'my-account.html',
    'options' => [
        'class' => 'payment-form'
    ]

]); ?>
<div class="payment-form">
    <input type="hidden" id="shipping-id" class="form-control" name="shipping-id" value="<?= $address && $address->id ? $address->id : '' ?>">
    <div class="form-group">
        <i class="icon user"></i>
        <input type="text" id="shipping-full_name" class="form-control" name="shipping-full_name" placeholder="<?= Yii::t('frontend', 'Full Name') ?>"  value="<?= $address && $address->first_name ? $address->first_name : '' ?>">
        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon phone"></i>
            <input type="number" id="shipping-phone" class="form-control" name="shipping-phone" placeholder="<?= Yii::t('frontend', 'Phone') ?>" value="<?= $address && $address->phone ? $address->phone : '' ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon email"></i>
            <input type="email" id="shipping-email" class="form-control" name="shipping-email" placeholder="<?= Yii::t('frontend', 'Email') ?>" value="<?= $address && $address->email ? $address->email : '' ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="col-md-12 pl-0 pr-0">
        <?php
        if ($address->store_id == \common\components\StoreManager::STORE_ID) {
//                            echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
            echo $form->field($address, 'post_code', ['template' => "{input}\n{hint}\n{error}"])->widget(Select2::className(), [
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
            ]);
        } else {
            echo $form->field($address, 'address', ['template' => "{input}\n{hint}\n{error}"])->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')]);
        }
        echo $form->field($address, 'province_id', ['template' => "{input}\n{hint}\n{error}"])->widget(Select2::className(), [
            'data' => $address->getProvinces(),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => Yii::t('frontend', 'Choose the province'),
            ],
        ]);

        echo Html::hiddenInput('hiddenReceiverDistrictId', $address->district_id, ['id' => 'hiddenReceiverDistrictId']);

        echo $form->field($address, 'district_id', ['template' => "{input}\n{hint}\n{error}"])->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    "select2:select" => "function(event) { ;ws.payment.calculatorShipping(); }",
                ]
            ],
            'pluginOptions' => [
                'depends' => [Html::getInputId($address, 'province_id')],
                'placeholder' => Yii::t('frontend', 'Choose the district'),
                'url' => Url::toRoute(['sub-district']),
                'loadingText' => Yii::t('frontend', 'Loading district ...'),
                'initialize' => true,
                'params' => ['hiddenReceiverDistrictId']
            ],
        ]);

        ?>
    </div>
    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="shipping_is_default" name="shipping_is_default" <?= $address && $address->is_default ? 'checked' : '' ?>>
            <label for="shipping_is_default" class="form-check-label"><?= Yii::t('frontend','It\'s default shipping address.') ?></label>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <div class="help-block" id="error-message" style="color: red; font-weight: 700"></div>
        </div>
    </div>
</div>