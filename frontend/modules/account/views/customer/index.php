<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\CustomerSearch */
/* @var $model \common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $address \common\models\Address */
/* @var $addressShip \common\models\Address[] */
$this->params = [Yii::t('frontend','Home') => '/', Yii::t('frontend','My Account') => '/my-account.html'];
$contentForm = json_encode([
    'content' => \frontend\modules\account\views\widgets\FormAddressWidget::widget(),
    'title' => Yii::t('frontend','Add shipping address')
]);
$js = <<<JS
$('a[data-popup=modal]').on('click',function(event) {
        event.preventDefault();
        var uri = $(this).data('url');
        $('div#exampleModalAddress').modal('show').find('#modalContent').load(uri);
    });
$('#add-new').click(function() {
    var content = $contentForm;
  ws.notifyConfirm(content.content,content.title,'default','ws.save_address()','',ws.t('Confirm'),ws.t('Close'),'btn btn-success','btn btn-warning',false);
});
$('#user_province_id').change(function() {
  ws.province_change('user_province_id','user_district_id');
});
$('#user_district_id').change(function() {
  ws.district_change('user_district_id','user_zip_code');
});
$('#user_zip_code').keyup(function() {
  ws.zipcode_keyup('user_zip_code','listZipCOdeUser');
});
$('#user_zip_code').change(function() {
  ws.zipcode_Change('user_zip_code','user_province_id','user_district_id');
});
$(document).ready(function() {
    $('.la-question').tooltip({'trigger':'hover'});
});
JS;
$this->registerJs($js);

$urlAjaxUrl = Url::toRoute(['/data/get-zip-code']);

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
<div class="be-acc">
    <div class="ba-block1">
        <?php $form = ActiveForm::begin([
                'action' => 'my-account.html',
            'options' => [
                'class' => 'payment-form'
            ]

        ]); ?>
        <div class="form-group">
            <?= $form->field($model, 'first_name', ['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'phone', ['template' => " <i class=\"icon phone\"></i>{input}\n{hint}\n{error}"])->input('number') ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'email', ['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->input('email') ?>
        </div>
        <div class="col-md-12 pl-0 pr-0">
            <?php
            if ($model->store_id == \common\components\StoreManager::STORE_ID) {
//                            echo $form->field($shippingForm, 'receiver_post_code')->textInput(['placeholder' => Yii::t('frontend', 'Enter your post code')])->label(Yii::t('frontend', 'Post Code'));
                echo $form->field($address, 'post_code', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->widget(Select2::className(), [
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
                echo $form->field($address, 'address', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->textInput(['placeholder' => Yii::t('frontend', 'Enter your address')]);
            }
            echo $form->field($address, 'province_id', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->widget(Select2::className(), [
                'data' => $address->getProvinces(),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => Yii::t('frontend', 'Choose the province'),
                ],
            ]);

            echo Html::hiddenInput('hiddenReceiverDistrictId', $address->district_id, ['id' => 'hiddenReceiverDistrictId']);

            echo $form->field($address, 'district_id', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->widget(DepDrop::classname(), [
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
            <?= Html::submitButton(Yii::t('frontend', 'Confirm'), ['class' => 'btn btn-payment']) ?>
            <?php echo Html::a(Yii::t('frontend', 'Change password'), ['/secure/change-password'], ['class' => 'text-blue float-right pt-4 mt-3']); ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="config-acc">
            <div class="title"><?= Yii::t('frontend','Option Account') ?></div>
            <ul>
                <li>
                    <b><?= Yii::t('frontend','Country:') ?></b>
                    <span><?= $model->store->country_name ?></span>
                </li>
                <li>
                    <b><?= Yii::t('frontend','Language:') ?></b>
                    <span><?= Yii::t('frontend',$model->locale) ?></span>
                </li>
                <li>
                    <b><?= Yii::t('frontend','Currency:') ?></b>
                    <span><?= $model->store->currency ?></span>
                </li>
                <li>
                    <b><?= Yii::t('frontend','Level') ?></b>
                    <span class="label label-<?= $model->userLevel ?>"><?= Yii::t('frontend',$model->userLevel) ?></span>
                </li>
                <li>
                    <b><?= Yii::t('frontend','Account Boxme') ?>
                        <i class="la la-question" title="<?= Yii::t('frontend','Link your boxme account to get very more offers.') ?>"></i>
                    </b>
                    <span>
                        <?php
                        if($model->bm_wallet_id){
                            echo "<a  href='javascript: void(0);' onclick='ws.disconnectBoxme()'  class='badge badge-success'>".Yii::t('frontend','Connected')."</a>";
                        }else{
                            echo "<a href='javascript: void(0);' onclick='ws.showModal(\"loginBoxme\")' class='btn-link'>".Yii::t('frontend','Connect Boxme')."</a>";
                        }
                        ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="us-address">
        <div class="title"><?= Yii::t('frontend', 'Shipping address'); ?></div>
    </div>
    <div class="ba-block2">
        <div class="title-box">
            <div class="title"><?= Yii::t('frontend', 'Your shipping address'); ?>:</div>
<!--            <a href="javascript:void (0);" class="add-new" id="add-new">--><?//= Yii::t('frontend', 'Add new'); ?><!--</a>-->
            <?php
            echo Html::a('Add New',new \yii\web\JsExpression('javascript:void(0);'),[
                'data-url' => \yii\helpers\Url::toRoute(['/my-account/addAddress.html'],true),
                'data-target' => '#exampleModalAddress',
                'data-popup' => 'modal'
            ])
            ?>
        </div>
        <div class="row">
            <?php foreach ($addressShip as $add) {
                ?>
                <div class="col-md-6" style="border-right: 1px solid #7f7f7f7f; padding-bottom: 15px">
                    <div class="name-box">
                        <b><?= $add->last_name ?></b>
                        <a href="javascript:void (0);" onclick="ws.editAddress('<?= $add->id ?>')"><i class="fa fa-edit"></i> <?= Yii::t('frontend','Edit') ?></a>
                        <a href="javascript:void (0);" onclick="ws.removeAddress('<?= $add->id ?>')"><i class="fa fa-remove"></i> <?= Yii::t('frontend','Delete') ?></a>
                        <?php
                        if($add->is_default){?>
                            <span class="text-success font-weight-bold" style="font-size: 14px; margin-left: 15px"><i class="la la-tags"></i><?= Yii::t('frontend','Default') ?></span>
                        <?php }
                        ?>
                    </div>
                    <ul>
                        <li><?= $add->address ?></li>
                        <li><?= $add->address ?>,<br>
                            <?= $add->district_name ? ($add->district_name) : $add->district_id ?>,<?= $add->province_name ? ($add->province_name) : $add->province_id ?>,<?= $add->country_name ? ($add->country_name) : $add->country_id ?>.</li>
                        <li><?= Yii::t('frontend', 'Email: {email}',['email' => $add->email]); ?></li>
                        <li><?= Yii::t('frontend', 'Phone: {phone}',['phone' => $add->phone]); ?></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="modal" id="exampleModalAddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="card m-b-0">
                        <div class="card-body">
                            <div id="modalContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>