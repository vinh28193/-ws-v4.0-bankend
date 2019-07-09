<?php

use common\components\StoreManager;
use common\models\PaymentTransaction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

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
                    <td style="padding:20px 20px 20px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <?php echo Yii::t('frontend', 'Dear {customer},', [
                            'customer' => $paymentTransaction->transaction_customer_name
                        ]); ?></td>
                </tr>
                <tr>
                    <td style="padding:0px 20px 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <?php echo Yii::t('frontend', 'Thank you for payment', [
                            'name' => $storeManager->store->name,
                        ]); ?>

                    </td>
                </tr>
                <tr>
                    <td style="padding:0 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
                        <p>
                            <?php echo Yii::t('frontend', '{name} just received your payment with the following:', [
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
        <td style="padding: 0 20px 20px 20px;">
            <table style="border-collapse:collapse;color: #666666; border: 1px solid slategrey;" bgcolor="#ffffff"
                   cellpadding="0"
                   cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="padding:20px 20px 20px 20px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="50%" style="vertical-align: top">

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
    </tbody>
</table>