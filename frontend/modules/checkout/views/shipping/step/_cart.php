<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
?>

<div class="title">Đơn hàng <span>(<?= count($payment->orders) ?> sản phẩm)</span> <a href="#" class="far fa-edit"></a>
</div>
<div class="payment-box order">
    <div class="top">
        <ul class="order-list">
            <?php foreach ($payment->orders as $order): ?>
                <?php foreach ($order['products'] as $product): ?>
                    <li>
                        <div class="thumb">
                            <img src="<?= $product['link_img']; ?>" alt="<?= $product['product_name']; ?>"/>
                        </div>
                        <div class="info">
                            <div class="left">
                                <a href="<?= $product['product_link']; ?>"
                                   class="name"><?= $product['product_name']; ?></a>
                                <?php
                                if(isset($order['seller']) && ($seller = $order['seller']) !== null && is_array($seller)){
                                    echo '<p>Bán bởi: <a href="' . ($seller['seller_link_store'] !== null ? $seller['seller_link_store'] : "#") . '">' . $seller['seller_name'] . '</a></p>';
                                }

                                ?>
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
                                    <li><?= $product['portal']; ?></li>
                                    <li>x<?= $product['quantity_customer']; ?></li>
                                    <li><?= $product['total_price_amount_local']; ?><i class="currency">đ</i></li>
                                </ol>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
        <div class="coupon" id="discountInputCoupon">
            <label>Mã giảm giá:</label>
            <div class="input-group discount-input">
                <input type="text" class="form-control" name="couponCode">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="applyCouponCode">Áp dụng</button>
                </div>
            </div>
        </div>
        <span class="text-danger" id="discountErrors" style="display: none"></span>
    </div>
    <ul class="billing" id="billingBox">
        <li id="discountPrice" style="display: <?= $payment->total_discount_amount > 0 ? 'block' : 'none' ?>">
            <div class="left">Khuyến mãi giảm giá:</div>
            <div class="right"><span><?= $payment->total_discount_amount; ?></span></div>
        </li>
        <li id="finalPrice">
            <div class="left">Tổng tiền thanh toán:</div>
            <div class="right"><span><?= $payment->total_amount_display; ?></span></div>
        </li>
    </ul>
</div>
