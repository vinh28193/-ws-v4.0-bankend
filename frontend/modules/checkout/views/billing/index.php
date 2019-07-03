<?php

use yii\helpers\ArrayHelper;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
/* @var common\models\PaymentTransaction $paymentTransaction */
/* @var frontend\modules\payment\models\Order $order */

$this->title = Yii::t('frontend', 'Order {code} billing', ['code' => $order->ordercode]);
?>

<style type="text/css">
    .btn-continue {
        border-radius: 3px;
        border: 1px solid #d25e0d;
        background-image: linear-gradient(180deg, #ff9d17 0%, #e67424 100%);
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }
</style>
<div class="container">

    <div class="card card-checkout card-order" data-key="<?= $order->ordercode ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <?= Yii::t('frontend', 'Order code: {code}', [
                            'code' => $order->ordercode
                        ]); ?>
                        <small><?= Yii::t('frontend', 'Create at: {time}', ['time' => Yii::$app->getFormatter()->asDatetime($order->created_at)]); ?></small>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5><?= Yii::t('frontend', 'Buyer information'); ?></h5>
                    <p class="mt-2 mb-1">
                        <?php echo Yii::t('frontend', 'Name : {name}', [
                            'name' => $order->buyer_name
                        ]); ?>
                    </p>
                    <p class="mb-1">
                        <?php
                        $address = [$order->buyer_address];
                        if ($order->buyer_post_code !== null) {
                            $address[] = $order->buyer_post_code;
                        }
                        $address[] = $order->buyer_district_name;
                        $address[] = $order->buyer_province_name;
                        ?>
                        <?php echo Yii::t('frontend', 'Address : {address}', [
                            'address' => implode(' - ', $address)
                        ]); ?>
                    </p>
                    <p class="mb-1">
                        <?php echo Yii::t('frontend', 'Phone : {phone}', [
                            'phone' => $order->buyer_phone
                        ]); ?>
                    </p>
                    <p class="mb-1">
                        <?php echo Yii::t('frontend', 'Email : {email}', [
                            'email' => $order->buyer_email
                        ]); ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5><?= Yii::t('frontend', 'Receiver information'); ?></h5>
                    <p class="mt-2 mb-1">
                        <?php echo Yii::t('frontend', 'Name : {name}', [
                            'name' => $order->receiver_name
                        ]); ?>
                    </p>
                    <p class="mb-1">
                        <?php
                        $address = [$order->receiver_address];
                        if ($order->receiver_post_code !== null) {
                            $address[] = $order->receiver_post_code;
                        }
                        $address[] = $order->receiver_district_name;
                        $address[] = $order->receiver_province_name;
                        ?>
                        <?php echo Yii::t('frontend', 'Address : {address}', [
                            'address' => implode(' - ', $address)
                        ]); ?>
                    </p>
                    <p class="mb-1">
                        <?php echo Yii::t('frontend', 'Phone : {phone}', [
                            'phone' => $order->receiver_phone
                        ]); ?>
                    </p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 pt-2">
                    <div style="border-top: 1px silver solid;padding-top: 1rem"></div>
                    <span style="display: inline-block;vertical-align: middle;margin-right: 30px;"><?= Yii::t('frontend', 'Status: {status}', ['status' => $order->total_paid_amount_local > 0 ? Yii::t('frontend', 'Paid') : Yii::t('frontend', 'Unpaid')]); ?></span>
                    <span style="display: inline-block;vertical-align: middle;margin-right: 30px;"><?= Yii::t('frontend', 'Payment type: {type}', ['type' => $order->payment_type]); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title seller">
                        <?php
                        $seller = $order->seller;
                        ?>
                        <?= $seller->portal; ?> store: <a
                                href="<?= $seller->seller_link_store !== null ? $seller->seller_link_store : '#'; ?>"><?= $seller->seller_name; ?></a>
                        (<?= count($order->products); ?> items)
                        <!--                        <i class="fa fa-user"></i>--><?php //echo Yii::t('frontend', 'Amount needed to prepay'); ?>
                    </div>
                </div>
            </div>
            <div class="product-header row pt-2">
                <div class="col-md-4"></div>
                <div class="col-md-2 text-center"><?= Yii::t('frontend', 'Price'); ?></div>
                <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Quantity'); ?></div>
                <div class="col-md-2 text-center"><?= Yii::t('frontend', 'Tax/Domestic shipping'); ?></div>
                <div class="col-md-1 text-center"><?= Yii::t('frontend', 'Purchase Fee'); ?></div>
                <div class="col-md-2 text-center"><?= Yii::t('frontend', 'Total amount'); ?></div>
            </div>
            <div class="row product-list">
                <?php foreach ($order->products as $product): ?>
                    <?php
                    $productFees = ArrayHelper::index($product->productFees, null, 'name');
                    ?>

                    <div class="col-md-12 product-item">
                        <div class="row product">
                            <div class="col-md-1 pt-2">
                                <img src="<?= $product->link_img; ?>"
                                     alt="<?= $product->product_name; ?>" width="80%" height="100px"
                                     title="<?= $product->product_name; ?>">
                            </div>
                            <div class="col-md-3 text-center pt-4">
                                <?= $product->product_name; ?>
                            </div>
                            <div class="col-md-2 text-center pt-4">
                                <?php echo $storeManager->showMoney($product->price_amount_local); ?>
                            </div>
                            <div class="col-md-1 text-center pt-4">x <?php echo $product->quantity_customer; ?></div>
                            <div class="col-md-2 text-center pt-4">
                                <?php
                                $usPrice = 0;
                                foreach (['tax_fee', 'shipping_fee'] as $feeUs) {
                                    if (isset($productFees[$feeUs])) {
                                        foreach ($productFees[$feeUs] as $productFee) {
                                            /** @var $productFee common\models\db\TargetAdditionalFee */
                                            $usPrice += (int)$productFee->local_amount;
                                        }
                                    }
                                }
                                echo $storeManager->showMoney($usPrice);
                                ?>
                            </div>
                            <div class="col-md-1 text-center pt-4">
                                <?php
                                $purchaseFee = 0;
                                $purchaseFee = isset($productFees['purchase_fee']) ? $productFees['purchase_fee'][0]->local_amount : $purchaseFee;
                                echo $storeManager->showMoney($purchaseFee);
                                ?>
                            </div>
                            <div class="col-md-2 text-center pt-4 text-danger">
                                <?php
                                echo $storeManager->showMoney($product->total_final_amount_local);
                                ?>
                            </div>
                        </div>


                    </div>
                <?php endforeach; ?>
            </div>
            <div class="row additional-fee">
                <div class="col-md-7 col-sm-12"></div>
                <div class="col-md-5 col-sm-12">
                    <div class="additional-list">
                        <table class="table table-borderless table-fee">
                            <tr data-role="fee" data-fee="international_shipping_fee">
                                <th class="header"><?= Yii::t('frontend', 'Shipping fee'); ?>
                                    <?php
                                    $tooltipMessage = Yii::t('frontend', 'for {weight} {dram}', [
                                        'weight' => $order->total_weight_temporary,
                                        'dram' => 'kg'
                                    ])
                                    ?>
                                    <i class="la la-question-circle code-info" data-toggle="tooltip"
                                       data-placement="top" title="<?= $tooltipMessage; ?>"
                                       data-original-title="<?= $tooltipMessage; ?>"></i>
                                </th>
                                <td class="value"><?= $storeManager->showMoney($order->total_intl_shipping_fee_local); ?></td>
                            </tr>
                            <tr class="courier">
                                <th class="header"><?php echo $order->courier_name; ?></th>
                                <td class="text-right"><?php echo Yii::t('frontend', '{days} days', ['days' => $order->courier_delivery_time]); ?></td>
                            </tr>
                            <tr class="discount-detail">
                                <th class="header"><?= Yii::t('frontend', 'Coupon code'); ?> <span
                                            class="coupon-code"></span>
                                </th>
                                <td class="value">
                                    <div class="input-group discount-input" style="margin-bottom: 1rem">
                                        <input type="text" class="form-control" name="couponCode">
                                        <div class="input-group-append">
                                            <button data-key="<?php echo $order->ordercode; ?>"
                                                    class="btn btn-outline-secondary" type="button"
                                                    id="applyCouponCode"><?php echo Yii::t('frontend', 'Apply'); ?></button>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="discountErrors" style="display: none"></span>
                                    <span class="discount-amount" style="display: none"><span
                                                class="discount-value"></span>
                                            <i class="la la-times text-danger del-coupon"
                                               onclick="ws.payment.removeCouponCode('<?= $order->ordercode; ?>')"></i></span>
                                </td>
                            </tr>

                            <tr class="final-amount">
                                <th class="header"><?= Yii::t('frontend', 'Amount must to pre-pay') ?></th>
                                <td class="value"><?= $storeManager->showMoney($order->getTotalFinalAmount()); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php if (!(int)$order->total_paid_amount_local > 0): ?>
                        <div class="button-group text-right">
                            <button class="btn btn-continue" data-toggle="modal" data-target="#otherMethods">Choose
                                other payment method
                            </button>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="otherMethods" tabindex="-1" role="dialog" aria-labelledby="otherMethodsTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="otherMethodsTitle"><?php echo Yii::t('frontend', 'Select a payment method'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $payment; ?>
            </div>
        </div>
    </div>
</div>