<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

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
?>
<div class="be-acc">
    <div class="ba-block1">
        <?php $form = ActiveForm::begin([
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
        <?php
        $provider = \common\models\SystemStateProvince::select2DataForCountry($address->country_id ? $address->country_id : $model->store->country_id);
        $provi = ArrayHelper::map($provider, 'id', 'name');
        $district = \common\models\SystemDistrict::select2Data($address->province_id ? $address->province_id : array_key_first($provi));
        $dist = ArrayHelper::map($district, 'id', 'name');
        ?>
        <div class="form-group">
            <?= $form->field($address, 'province_id', ['template' => " <i class=\"icon globe\"></i>{input}\n{hint}\n{error}"])->dropDownList($provi, ['id' => 'user_province_id']); ?>
        </div>
        <div class="form-group">
            <?= $form->field($address, 'district_id', ['template' => " <i class=\"icon city\"></i>{input}\n{hint}\n{error}"])->dropDownList($dist,['id' => 'user_district_id']); ?>
        </div>
        <?php if($model->store_id == \common\components\StoreManager::STORE_ID){?>
            <div class="form-group">
                <?= $form->field($address, 'post_code', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true,'placeholder' => 'Zip Code','id' => 'user_zip_code','list' => 'listZipCOdeUser']) ?>
                <datalist id="listZipCOdeUser">

                </datalist>
            </div>
        <?php } ?>
        <div class="form-group">
            <?= $form->field($address, 'address', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
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
            <a href="javascript:void (0);" class="add-new" id="add-new"><?= Yii::t('frontend', 'Add new'); ?></a>
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
