<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var \frontend\modules\checkout\Payment $payment */
?>

<div class="title">Đơn hàng <span>(<?= count($payment->orders) ?> sản phẩm)</span> <a href="#" class="far fa-edit"></a>
</div>
<div class="payment-box order">
    <div class="top">
        <ul class="order-list">
            <li>
                <div class="thumb">
                    <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt=""/>
                </div>
                <div class="info">
                    <div class="left">
                        <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink Gold-Tone 30mm
                            Watch</a>
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
                        <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink Gold-Tone 30mm
                            Watch</a>
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
        <div class="coupon" id="discountCoupon">
            <label>Mã giảm giá:</label>
            <div class="input-group discount-input">
                <input type="text" class="form-control" name="couponCode">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="applyCouponCode">Áp dụng</button>
                </div>
            </div>
        </div>
    </div>
    <ul class="billing" id="billingBox"></ul>
</div>
