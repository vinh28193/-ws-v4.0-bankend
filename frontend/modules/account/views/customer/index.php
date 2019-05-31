<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
            <?= $form->field($model, 'last_name', ['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'phone', ['template' => " <i class=\"icon phone\"></i>{input}\n{hint}\n{error}"])->input('number') ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'email', ['template' => " <i class=\"icon email\"></i>{input}\n{hint}\n{error}"])->input('email') ?>
        </div>
        <?php
        $provider = \common\models\SystemStateProvince::find()->all();
        $provi = ArrayHelper::map($provider, 'id', 'name');
        ?>
        <div class="form-group">
            <?= $form->field($address, 'province_id', ['template' => " <i class=\"icon globe\"></i>{input}\n{hint}\n{error}"])->dropDownList($provi, ['id' => 'province_id']); ?>
        </div>
        <?php
        $district = \common\models\SystemDistrict::find()->all();
        $distr = ArrayHelper::map($district, 'id', 'name')
        ?>
        <div class="form-group">
            <?= $form->field($address, 'district_id', ['template' => " <i class=\"icon city\"></i>{input}\n{hint}\n{error}"])->widget(DepDrop::classname(), [
                'data' => $distr,
                'options' => ['id' => 'district_id'],
                'pluginOptions' => [
                    'depends' => ['province_id'],
                    'placeholder' => Yii::t('frontend', 'Select province...'),
                    'url' => Url::to(['customer/subcat'])
                ]
            ]); ?>
        </div>
        <div class="form-group">
            <?= $form->field($address, 'address', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('frontend', 'Confirm'), ['class' => 'btn btn-payment']) ?>
            <?php echo Html::a(Yii::t('frontend', 'Change password'), ['/secure/change-password'], ['class' => 'text-blue float-right pt-4 mt-3']); ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="config-acc">
            <div class="title">Tùy chọn tài khoản</div>
            <ul>
                <li>
                    <b>Quốc tịch:</b>
                    <span><?= $model->store->name ?></span>
                </li>
                <li>
                    <b>Ngôn ngữ:</b>
                    <span>Tiếng việt</span>
                </li>
                <li>
                    <b>Tiền tệ:</b>
                    <span>VNĐ</span>
                </li>
                <li>
                    <b>Cấp thành viên:</b>
                    <span>Basic</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="us-address">
        <div class="title"><?= Yii::t('frontend', 'Shipping address'); ?></div>
        <div class="code"><?= Yii::t('frontend', 'Shipping code'); ?> : <b><?= $model->verify_code ?></b></div>
    </div>
    <div class="ba-block2">
        <div class="title-box">
            <div class="title"><?= Yii::t('frontend', 'Your shipping address'); ?>:</div>
            <a href="#" class="add-new"><?= Yii::t('frontend', 'Add new'); ?></a>
        </div>
        <div class="row">
            <?php
            foreach ($addressShip as $add) {
                ?>
                <div class="col-md-6">
                    <div class="name-box">
                        <b><?= $add->last_name ?></b>
                        <a href="#"><i class="fa fa-edit"></i></a>
                        <a href="#"><i class="fa fa-remove"></i></a>
                    </div>
                    <ul>
                        <li><?= $add->address ?></li>
                        <li><?= Yii::t('frontend', 'Email: {email}',['email' => $add->email]); ?></li>
                        <li><?= Yii::t('frontend', 'Phone: {phone}',['phone' => $add->phone]); ?></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
