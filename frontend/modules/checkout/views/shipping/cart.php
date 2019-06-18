<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */

$css = <<<CSS
    .order-summary {
        background: #f2f3f5;
        border-top: 1px solid #ebebeb;
        padding: 15px;
        font-size: 12px;
        font-weight: 500;
    }
    .order-summary li{
        display: flex;
        justify-content: space-between;
    }
CSS;
$this->registerCss($css);

$storeManager = Yii::$app->storeManager;
?>

<div class="title">
    <?= Yii::t('frontend', 'Orders <span>({count} item)</span>', ['count' => count($payment->orders)]); ?>
    <!--    <a href="#" class="far fa-edit"></a>-->
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
                        <?= Yii::t('frontend', 'Sell by'); ?>:&nbsp;<a style="color: #2b96b6;"
                                                                       href="<?= isset($seller['seller_link_store']) ? $seller['seller_link_store'] : '#' ?>"><?= isset($seller['seller_name']) ? $seller['seller_name'] : 'Unknown'; ?></a>
                        &nbsp;(<?= count($products); ?> <?= Yii::t('frontend', 'products') ?>)
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
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star-half-o"></i>
                                    <i class="la la-star-o"></i>
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

    </div>
    <ul id="orderFee" class="order-summary">
        <li>
            <div class="left"><?= Yii::t('frontend', 'Order amount') ?>:</div>
            <div class="right">
                <span>
                    <?= $storeManager->showMoney($payment->total_order_amount); ?>
                </span>
            </div>
        </li>
        <li>
            <div class="left"><?= Yii::t('frontend', 'International Shipping') ?>:</div>
            <div class="right">
                <span>
                    <?php echo $storeManager->showMoney($payment->getAdditionalFees(false)['international_shipping_fee']); ?>
                </span>
            </div>
        </li>
        <li>
            <div class="left"><?= Yii::t('frontend', 'Purchase fee') ?>:</div>
            <div class="right">
                <span>
                    <?php echo $storeManager->showMoney($payment->getAdditionalFees(false)['purchase_fee']); ?>
                </span>
            </div>
        </li>
    </ul>
    <div class="coupon" id="discountInputCoupon" style="border-top: none; padding:  15px;margin-top: 0px">
        <label><?= Yii::t('frontend', 'Coupon code'); ?>:</label>
        <div class="input-group discount-input">
            <input type="text" class="form-control" name="couponCode">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button"
                        id="applyCouponCode"><?= Yii::t('frontend', 'Apply'); ?></button>
            </div>
        </div>
    </div>
    <span class="text-danger" id="discountErrors" style="display: none"></span>
    <ul class="billing" id="billingBox">
        <li id="discountPrice" style="display: <?= $payment->total_discount_amount > 0 ? 'block' : 'none' ?>">
            <div class="left"><?= Yii::t('frontend', 'Discount amount') ?>:</div>
            <div class="right">
                <span><?= $storeManager->showMoney($payment->total_discount_amount); ?></span>
            </div>
        </li>
        <li id="finalPrice">
            <div class="left"><?= Yii::t('frontend', 'Payment amount') ?>:</div>
            <div class="right">
                <span><?= $storeManager->showMoney($payment->getTotalAmountDisplay()); ?></span></div>
        </li>
    </ul>
</div>
