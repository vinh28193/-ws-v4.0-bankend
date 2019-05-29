<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
$storeManager = Yii::$app->storeManager;
?>

<div class="title">Đơn hàng <span>(<?= count($payment->orders) ?> item)</span> <a href="#" class="far fa-edit"></a>
</div>
<div class="payment-box order">
    <div class="top">

        <?php foreach ($payment->orders as $order): ?>
            <?php
            $seller = ArrayHelper::getValue($order, 'seller', []);
            $products = ArrayHelper::getValue($order, 'products', []);
            ?>

            <ul class="order-list" style="margin-bottom: 10px;border-bottom: 1px dashed #ebebeb">
                <?php if (!empty($seller)): ?>
                    <li>
                        Bán bởi:&nbsp;<a style="color: #2b96b6;"
                                         href="<?= isset($seller['seller_link_store']) ? $seller['seller_link_store'] : '#' ?>"><?= isset($seller['seller_name']) ? $seller['seller_name'] : 'Unknown'; ?></a>
                        &nbsp;(<?= count($products); ?> sản phẩm)
                    </li>
                <?php endif; ?>
                <?php foreach ($products as $product): ?>
                    <li style="margin-top: 0px;margin-bottom: 16px">
                        <div class="thumb">
                            <img src="<?= $product['link_img']; ?>" alt="<?= $product['product_name']; ?>"/>
                        </div>
                        <div class="info">
                            <div class="left">
                                <a href="<?= $product['product_link']; ?>"
                                   class="name"><?= $product['product_name']; ?></a>
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
                                    <li>&nbsp;</li>
                                    <li>x<?= $product['quantity_customer']; ?></li>
                                    <li><?= $storeManager->showMoney($product['total_price_amount_local']); ?>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>

        <div class="coupon" id="discountInputCoupon" style="border-top: none; padding:  0;margin-top: 16px;">
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
            <div class="right">
                <span><?= $storeManager->showMoney($payment->total_discount_amount); ?></span>
            </div>
        </li>
        <li id="finalPrice">
            <div class="left">Tổng tiền thanh toán:</div>
            <div class="right">
                <span><?= $storeManager->showMoney($payment->total_amount_display); ?></span></div>
        </li>
    </ul>
</div>
