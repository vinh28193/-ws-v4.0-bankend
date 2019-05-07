<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Customers';
//$this->params['breadcrumbs'][] = $this->title;
echo Html::getInputName($model, 'last_name');
?>
<div class="be-acc">
    <div class="ba-block1">
            <?php $form = ActiveForm::begin([
                    'options' => [
                            'class' => 'payment-form'
                    ]

            ]); ?>
            <div class="form-group">
                <?= $form->field($model, 'first_name',['template' => " <i class=\"icon user\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
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
                $provider=\common\models\SystemStateProvince::find()->all();
                $provi=ArrayHelper::map($provider,'id','name');
            ?>
            <div class="form-group">
                <?= $form->field($address, 'province_id', ['template' => " <i class=\"icon globe\"></i>{input}\n{hint}\n{error}"])->dropDownList($provi, ['id' => $model->address->province_id, 'onchange'=>'
				$.get( "'.Yii::$app->urlManager->createUrl('list-district?id=').'"+$(this).val(), function( data ) {
				});
			']);?>
            </div>
            <?php
            $district=\common\models\SystemDistrict::find()->all();
            $distr=ArrayHelper::map($district,'id','name')
            ?>
            <div class="form-group">
                <?= $form->field($address, 'district_id', ['template' => " <i class=\"icon city\"></i>{input}\n{hint}\n{error}"])->dropDownList($distr, ['id' => $model->address->district_id]) ?>
            </div>
            <div class="form-group">
                <?= $form->field($address, 'address', ['template' => " <i class=\"icon mapmaker\"></i>{input}\n{hint}\n{error}"])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Cập Nhập', ['class' => 'btn btn-payment']) ?>
                <a href="#" class="text-blue float-right pt-4 mt-3">Thay đổi mật khẩu</a>
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
        <div class="title">Địa chỉ tại Mỹ của bạn</div>
        <div class="code">Mã khách hàng của bạn : <b>49566665</b></div>
    </div>
    <div class="ba-block2">
        <div class="title-box">
            <div class="title">Địa chỉ giao hàng của bạn:</div>
            <a href="#" class="add-new">Thêm mới</a>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="name-box">
                    <b>LÊ NGỌC LINH</b>
                    <a href="#"><i class="icon edit"></i></a>
                    <a href="#"><i class="icon del"></i></a>
                </div>
                <ul>
                    <li>18 Tam Trinh- Quận Hai Bà Trưng- Tp.Hà Nội- Việt Nam</li>
                    <li>Email: linhktt@peacesoft.net</li>
                    <li>Số điện thoại: 0967985456</li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="name-box">
                    <b>LÊ NGỌC LINH</b>
                    <a href="#"><i class="icon edit"></i></a>
                    <a href="#"><i class="icon del"></i></a>
                </div>
                <ul>
                    <li>18 Tam Trinh- Quận Hai Bà Trưng- Tp.Hà Nội- Việt Nam</li>
                    <li>Email: linhktt@peacesoft.net</li>
                    <li>Số điện thoại: 0967985456</li>
                </ul>
            </div>
        </div>
    </div>
</div>
