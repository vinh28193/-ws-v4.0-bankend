<?php

use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var frontend\modules\checkout\models\ShippingForm $shippingForm */
/* @var array $provinces */
/* @var frontend\modules\checkout\Payment $payment */

?>
<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span>Đăng nhập</span></li>
        <li class="active"><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-2-content row">
        <div class="col-md-8">
            <div class="title">Thông tin mua hàng</div>
            <div class="payment-box">
                <?php
                $form = ActiveForm::begin([
                    'class' => 'payment-form'
                ]);
                echo Html::activeHiddenInput($shippingForm, 'customer_id');

                echo $form->field($shippingForm, 'buyer_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_phone', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'buyer_province_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList($provinces);

                echo $form->field($shippingForm, 'buyer_district_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'options' => ['id' => 'district_id'],
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'buyer_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district'])
                    ]
                ]);

                echo $form->field($shippingForm, 'note_by_customer', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textarea(['rows' => 3, 'placeholder' => 'Ghi chú thêm ( không bắt buộc)']);

                echo $form->field($shippingForm, 'save_my_address', [
                    'template' => '{input}{hint}{error}',
                    'options' => [
                        'class' => 'check-info'
                    ]
                ])->checkbox();

                echo '<a href="#" class="other-receiver"><i class="fas fa-check-circle"></i> Thông tin người nhận
                hàng khác người đặt hàng</a>';

                echo $form->field($shippingForm, 'receiver_name', [
                    'template' => '<i class="icon user"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_phone', [
                    'template' => '<i class="icon phone"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_phone', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->textInput();

                echo $form->field($shippingForm, 'receiver_province_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->dropDownList($provinces);

                echo $form->field($shippingForm, 'receiver_district_id', [
                    'template' => '<i class="icon email"></i>{input}{hint}{error}',
                    'options' => ['class' => 'form-group']
                ])->widget(DepDrop::classname(), [
                    'options' => ['id' => 'district_id'],
                    'pluginOptions' => [
                        'depends' => [Html::getInputId($shippingForm, 'receiver_province_id')],
                        'placeholder' => 'Select District',
                        'url' => Url::toRoute(['sub-district'])
                    ]
                ]);
                echo Html::submitButton('Chọn hình thức thanh toán', ['class' => 'btn btn-payment btn-block']);
                ActiveForm::end();
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="title">Đơn hàng <span>(2 sản phẩm)</span> <a href="#" class="far fa-edit"></a></div>
            <div class="payment-box order">
                <div class="top">
                    <ul class="order-list">
                        <li>
                            <div class="thumb">
                                <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt=""/>
                            </div>
                            <div class="info">
                                <div class="left">
                                    <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink
                                        Gold-Tone 30mm Watch</a>
                                    <p>Bán bởi: <a href="#">Multiple supplier.</a></p>
                                    <div class="rate">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <div class="right">
                                    <ol class="price">
                                        <li>5.800.000 <i class="currency">đ</i></li>
                                        <li>x1</li>
                                        <li>5.800.000 <i class="currency">đ</i></li>
                                    </ol>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt=""/>
                            </div>
                            <div class="info">
                                <div class="left">
                                    <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink
                                        Gold-Tone 30mm Watch</a>
                                    <p>Bán bởi: <a href="#">Multiple supplier.</a></p>
                                    <div class="rate">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <div class="right">
                                    <ol class="price">
                                        <li>5.800.000 <i class="currency">đ</i></li>
                                        <li>x2</li>
                                        <li>11.600.000 <i class="currency">đ</i></li>
                                    </ol>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="coupon">
                        <label>Mã giảm giá:</label>
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Áp dụng</button>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="billing">
                    <li>
                        <div class="left">Khuyến mãi giảm giá:</div>
                        <div class="right">-100.000 <i class="currency">đ</i></div>
                    </li>
                    <li>
                        <div class="left">Tổng tiền thanh toán:</div>
                        <div class="right">17.400.000 <i class="currency">đ</i></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
