<?php

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use wallet\modules\v1\models\WalletTransaction;

/**
 * @var $transaction_code string
 * @var $transactionDetail array
 */

$this->title = Yii::t('frontend', 'Transaction detail');
$this->params = ['wallet', 'history'];
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => [Yii::t('frontend', 'Transaction') => '/my-weshop/wallet/history.html', Yii::t('frontend', 'Detail') => '#']]);
?>

<div class="be-box">
    <div class="be-top">
        <div class="title"><?= Yii::t('frontend', 'Transaction detail'); ?> #<b><?= strtoupper($transaction_code) ?></b>
        </div>
    </div>
    <div class="be-body">
        <?php if ($transactionDetail) { ?>
            <table class="table table-condensed" style="width: fit-content;">
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Transaction code') ?>
                    </td>
                    <td>
                        <b><span id="wallet_transaction_code"><?= $transactionDetail['wallet_transaction_code'] ?></span></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Transaction type') ?>
                    </td>
                    <td>
                        <b><span id="type"><?= WeshopHelper::getTypeTransaction($transactionDetail['type']) ?></span></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Transaction at') ?>
                    </td>
                    <td>
                        <b><span id="create_at"><?= $transactionDetail['create_at'] ?></span></b>
                    </td>
                </tr>
                <?php if ($transactionDetail['type'] === WalletTransaction::TYPE_PAY_ORDER) { ?>
                    <tr>
                        <td>
                            <i class="fa fa-caret-right"></i>
                            <?= Yii::t('frontend', 'Order code'); ?>
                        </td>
                        <td>
                            <b><span id="order_number"><?= $transactionDetail['order_number'] ?></span></b>
                        </td>
                    </tr>
                <?php }
                ?>
                <tr>
                    <td style="/* padding-top: 20px */">
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Total amount') ?>:
                    </td>
                    <td style="/* padding-top: 20px */">
                        <b><span id="totalAmount"
                                 style="color: <?= in_array($transactionDetail['type'], [WalletTransaction::TYPE_REFUND, WalletTransaction::TYPE_PAY_ORDER, WalletTransaction::TYPE_WITH_DRAW, WalletTransaction::TYPE_ADDFEE]) ? "red" : 'green' ?>">
                                                <?= in_array($transactionDetail['type'], [WalletTransaction::TYPE_PAY_ORDER, WalletTransaction::TYPE_WITH_DRAW, WalletTransaction::TYPE_ADDFEE]) ? "-" : '+' ?> <?php
                                $amount = $transactionDetail['totalAmount'] ? $transactionDetail['totalAmount'] : ($transactionDetail['debit_amount'] ? $transactionDetail['debit_amount'] : $transactionDetail['credit_amount']);
                                echo number_format($amount);
                                ?> đ</span></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Transaction type') ?>:
                    </td>
                    <td>
                        <b><span id="payment_method"> <?= $transactionDetail['type'] === WalletTransaction::TYPE_TOP_UP || $transactionDetail['type'] === WalletTransaction::TYPE_REFUND ? $transactionDetail['payment_method'] : $transactionDetail['payment_provider_name'] ?></span></b>
                    </td>
                </tr>
                <?php
                $jsonContent = json_decode($transactionDetail['request_content']);
                if ($transactionDetail['type'] === WalletTransaction::TYPE_WITH_DRAW) {
                    if ($transactionDetail['payment_method'] === "NL") {
                        ?>
                        <tr>
                            <td>
                                <i class="fa fa-caret-right"></i>
                                <?= Yii::t('frontend', 'Card number') ?>:
                            </td>
                            <td>
                                <b><span id="account_receiver"><?= $jsonContent->numberCard ?></span></b>
                            </td>
                        </tr>
                    <?php } elseif ($transactionDetail['payment_method'] === "BANK") {
                        ?>
                        <tr>
                            <td>
                                <i class="fa fa-caret-right"></i>
                                <?= Yii::t('frontend', 'Bank code') ?>:
                            </td>
                            <td>
                                <b><span id="payment_bank_code"><?= $transactionDetail['payment_bank_code'] ?></span></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-caret-right"></i>
                                <?= Yii::t('frontend', 'Cardholder name') ?>:
                            </td>
                            <td>
                                <b><span id="cardholderName"><?= $jsonContent->cardholderName ?></span></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-caret-right"></i>
                                <?= Yii::t('frontend', 'Card number') ?>:
                            </td>
                            <td>
                                <b><span id="numberCard"><?= $jsonContent->numberCard ?></span></b>
                            </td>
                        </tr>
                    <?php }
                }
                ?>
                <tr>
                    <td style="/* padding-top: 20px */">
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Status') ?>::
                    </td>
                    <td style="/* padding-top: 20px */">
                        <span class="badge badge-<?= WeshopHelper::getStatusTransactionColor($transactionDetail['status']); ?>"><?= WeshopHelper::getStatusTransaction($transactionDetail['status']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::t('frontend', 'Status updated at'); ?>:
                    </td>
                    <td>
                        <b><span id="wallet-current"><?php
                                switch ($transactionDetail['status']) {
                                    case 0:
                                        echo $transactionDetail['update_at'] ? $transactionDetail['update_at'] : $transactionDetail['create_at'];
                                        break;
                                    case 1:
                                        echo $transactionDetail['update_at'];
                                        break;
                                    case 2:
                                        echo $transactionDetail['complete_at'];
                                        break;
                                    case 3:
                                        echo $transactionDetail['cancel_at'];
                                        break;
                                    case 4:
                                        echo $transactionDetail['fail_at'];
                                        break;
                                }
                                ?></span></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <?php if ($transactionDetail['type'] === WalletTransaction::TYPE_TOP_UP && $transactionDetail['status'] === 0) {
                            $payment = \common\models\PaymentTransaction::findOne(['transaction_code' => $transactionDetail['order_number']]);
                            if ($payment && $payment->third_party_transaction_link) {
                                ?>
                                <a class="btn btn-outline-success" href="<?= $payment->third_party_transaction_link ?>">
                                    <?= Yii::t('frontend', 'Continue to payment'); ?>
                                </a>
                            <?php }
                        } elseif ($transactionDetail['type'] === WalletTransaction::TYPE_WITH_DRAW) {
                            if (in_array($transactionDetail['status'], [0])) {
                                ?>
                                <a class="btn btn-outline-success"
                                   href="/my-weshop/wallet/withdraw/<?= $transactionDetail['wallet_transaction_code'] ?>.html">
                                    <?= Yii::t('frontend', 'Verify'); ?>

                                </a>
                            <?php }
                            if (in_array($transactionDetail['status'], [0, 1])) {
                                ?>
                                <a class="btn btn-outline-warning" href="javascript:void (0);"
                                   onclick="cancelWithdraw()">
                                    <?= Yii::t('frontend', 'Abort'); ?> </a>
                            <?php }
                        } ?>
                        <a class="btn btn-outline-info" href="/my-weshop/wallet/history.html">
                            <?= Yii::t('frontend', 'History'); ?>
                        </a>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <strong style="color: red"><?= Yii::t('frontend', 'Not found'); ?></strong>
        <?php } ?>
    </div>
</div>
