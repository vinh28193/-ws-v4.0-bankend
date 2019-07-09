<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $paymentTransaction common\models\PaymentTransaction */
/* @var $storeManager common\components\StoreManager */


?>
<table bgcolor="#ffffff" style="color: #ffffff; border: 1px solid gray" border="0" cellpadding="0" cellspacing="0"
       width="100%">
    <tbody>
    <tr>
        <td>
            <table style="color: #666666" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:20px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <?php echo Yii::t('frontend', 'Dear {customer},', [
                            'customer' => $paymentTransaction->transaction_customer_name
                        ]); ?></td>
                </tr>
                <tr>
                    <td style="padding:0px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <?php echo Yii::t('frontend', 'Thank you for using {name}\' service', [
                            'name' => $storeManager->store->name,
                        ]); ?>

                    </td>
                </tr>
                <tr>
                    <td style="padding:0px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <p>
                            <?php echo Yii::t('frontend', 'We hope you are satisfied with the shopping experience and selected products'); ?>
                            <br>
                            <?php echo Yii::t('frontend', '{name} just received your order information with the following order details:', [
                                'name' => $storeManager->store->name,
                            ]); ?>
                        </p>
                    </td>
                </tr>

                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 20px;">
            <table style="border-collapse:collapse;color: #666666; border: 1px solid slategrey" bgcolor="#ffffff"
                   cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:20px 20px 20px 20px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="50%" style="vertical-align: top">
                                    <h4 style="font-size: 14px; margin: 0 0 15px;text-transform: uppercase;"><?= Yii::t('frontend', 'Transaction information'); ?></h4>
                                    <p>
                                        <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                    </p>
                                    <p>
                                        <?= Yii::t('frontend', 'Transaction code: {code}', [
                                            'code' => Html::tag('b', $paymentTransaction->transaction_code)
                                        ]); ?>
                                    </p>
                                    <p>
                                        <?= Yii::t('frontend', 'Status: {status}', [
                                            'status' => Html::tag('b', \yii\helpers\Inflector::camelize($paymentTransaction->transaction_status))
                                        ]); ?>
                                    </p>
                                    <p>
                                        <?= Yii::t('frontend', 'Create at: {time}', [
                                            'time' => Html::tag('b', Yii::$app->formatter->asDatetime($paymentTransaction->created_at))
                                        ]); ?>
                                    </p>
                                    <!--                                    <p>-->
                                    <!--                                        --><?php //echo  Yii::t('frontend', 'Total paid amount: {amount}', [
                                    //                                            'amount' => Html::tag('b', $storeManager->showMoney($paymentTransaction->transaction_amount_local), ['style' => 'color:red'])
                                    //                                        ]); ?>
                                    <!--                                    </p>-->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:10px 20px">
            <table style="border-collapse:collapse;color: #666666; border: 1px solid slategrey" bgcolor="#ffffff"
                   cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:15px 20px 1px 20px">
                        <table style="border-collapse:collapse; margin-bottom: 1.25rem" border="0" cellpadding="0"
                               cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:bold;padding:0 0 10px 0"
                                    align="left">
                                    <h4 style="font-size: 14px; margin: 0 0 15px;text-transform: uppercase;"><?= Yii::t('frontend', 'Order details'); ?></h4>
                                    <p>
                                        <i style="display: block; width: 50px; height: 1px; background: #2796b6;"></i>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif;font-size:18px;padding:0 0 10px 0">
                                    <?php
                                    $totalShippingFee = 0;
                                    foreach ($paymentTransaction->childPaymentTransaction as $idx => $childPaymentTransaction):
                                        if (($order = $childPaymentTransaction->order) === null) {
                                            continue;
                                        }
                                        $idx += 1;
                                        $countProducts = count($order->products);
                                        $totalShippingFee += $order->total_intl_shipping_fee_local;
                                        ?>

                                        <table style="margin-bottom:1rem;font-family:Arial,Helvetica,sans-serif;font-size:12px;"
                                               border="0"
                                               cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                            <tr>
                                                <td colspan="2" style="padding:0.25rem 0;">
                                                    <?php echo Yii::t('frontend', 'Order: {code} sold by {seller} in {portal} ({count}) items', [
                                                        'code' => Html::tag('b', $order->ordercode),
                                                        'seller' => Html::tag('span', $order->seller_name, ['style' => 'color:#2b96b6']),
                                                        'portal' => strtolower($order->portal) === 'ebay' ? 'eBay' : 'Amazon',
                                                        'count' => $countProducts
                                                    ]); ?>
                                                </td>
                                            </tr>
                                            <?php

                                            foreach ($order->products as $product):
                                                $totalProductAmount = 0;
                                                foreach ($product->productFees as $productFee) {
                                                    $totalProductAmount += $productFee->local_amount;
                                                }
                                                ?>
                                                <tr>
                                                    <td style="padding:0.25rem 0;" width="70%" align="left">
                                                        <?php echo "<b>P$product->id</b> - " . $product->product_name ?>
                                                    </td>
                                                    <td style="padding:0.25rem 0; color: red" align="right">
                                                        <?php echo $storeManager->showMoney($totalProductAmount); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if ($idx < $countProducts + 1): ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <i style="display: block; height: 1px; background: #2796b6;"></i>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <i style="display: block; width: 50%; height: 1px; background: #2796b6;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <table width="50%" cellspacing="0" cellpadding="0">
                                        <tbody>
                                        <tr>
                                            <td align="left" style="padding:0.25rem 0;">
                                                <?= Yii::t('frontend', 'Shipping Fee') ?>
                                            </td>
                                            <td align="right" style="color: red;padding:0.25rem 0;">
                                                <?= $storeManager->showMoney($totalShippingFee); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding:0.25rem 0;">
                                                <?= Yii::t('frontend', 'Estimated time') ?>
                                            </td>
                                            <td align="right" style="color: red;padding:0.25rem 0;">
                                                <?= Yii::t('frontend', '{days} days', [
                                                    'days' => implode('-', explode(' ', $paymentTransaction->courier_delivery_time))
                                                ]); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding:0.25rem 0;">
                                                <?= Yii::t('frontend', 'Total amount') ?>
                                            </td>
                                            <td align="right" style="color: red;padding:0.25rem 0;">
                                                <?= $storeManager->showMoney($paymentTransaction->transaction_amount_local); ?>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php if ($paymentTransaction->transaction_status !== 'SUCCESS'): ?>
                                <tr>
                                    <td align="center" colspan="2" style="padding:1.25rem 0;">
                                        <a href="<?= \frontend\modules\payment\PaymentService::createCheckoutUrl(null, $paymentTransaction->transaction_code) ?>">
                                            <?= Yii::t('frontend', 'Click here to re-payment') ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>