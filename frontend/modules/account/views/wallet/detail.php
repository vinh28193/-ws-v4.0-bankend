<?php

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use wallet\modules\v1\models\WalletTransaction;
use yii\helpers\ArrayHelper;

/**
 * @var $transaction_code string
 * @var $transactionDetail array
 */

$this->title = 'Chi tiết giao dịch';
echo HeaderContentWidget::widget(['title' => $this->title,'stepUrl' => ['Giao dịch' => '/my-weshop/wallet/history.html','Chi tiết' => '#']]);
?>

<div class="be-box">
    <div class="be-top">
        <div class="title">Chi tiết giao dịch #<b><?= strtoupper($transaction_code )?></b></div>
    </div>
    <div class="be-body">
        <?php if ($transactionDetail){?>
        <table class="table table-condensed" style="width: fit-content;">
            <tr>
                <td>
                    <i class="fa fa-caret-right"></i>
                    Mã Giao Dịch:
                </td>
                <td>
                    <b><span id="wallet-current"><?= $transactionDetail['wallet_transaction_code'] ?></span></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i class="fa fa-caret-right"></i>
                    Thời Gian Giao Dịch:
                </td>
                <td>
                    <b><span id="wallet-current"><?= $transactionDetail['create_at'] ?></span></b>
                </td>
            </tr>
            <?php if ($transactionDetail['type'] === WalletTransaction::TYPE_PAY_ORDER) { ?>
                <tr>
                    <td>
                        <i class="fa fa-caret-right"></i>
                        Mã Đơn Hàng:
                    </td>
                    <td>
                        <b><span id="wallet-current"><?= $transactionDetail['order_number'] ?></span></b>
                    </td>
                </tr>
            <?php }
            ?>
            <tr>
                <td style="/* padding-top: 20px */">
                    <i class="fa fa-caret-right"></i>
                    Tổng số tiền:
                </td>
                <td style="/* padding-top: 20px */">
                    <b><span id="wallet-current"
                             style="color: <?= $transactionDetail['type'] === WalletTransaction::TYPE_PAY_ORDER || $transactionDetail['type'] === WalletTransaction::TYPE_WITH_DRAW ? "red" : 'green' ?>">
                                                <?= $transactionDetail['type'] === WalletTransaction::TYPE_PAY_ORDER || $transactionDetail['type'] === WalletTransaction::TYPE_WITH_DRAW ? "-" : '+' ?> <?php
                            $amount = $transactionDetail['totalAmount'] ? $transactionDetail['totalAmount'] : ($transactionDetail['debit_amount'] ? $transactionDetail['debit_amount'] : $transactionDetail['credit_amount']);
                            echo number_format($amount);
                            ?> đ</span></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i class="fa fa-caret-right"></i>
                    Phương Thức Thực Hiện:
                </td>
                <td>
                    <b><span id="wallet-current"> <?= $transactionDetail['type'] === WalletTransaction::TYPE_TOP_UP || $transactionDetail['type'] === WalletTransaction::TYPE_REFUND ? $transactionDetail['payment_method'] : $transactionDetail['payment_provider_name'] ?></span></b>
                </td>
            </tr>
            <?php
            $jsonContent = json_decode($transactionDetail['request_content']);
            if($transactionDetail['type'] === WalletTransaction::TYPE_WITH_DRAW){
                if($transactionDetail['payment_method'] === "NL"){
                    ?>
                    <tr>
                        <td>
                            <i class="fa fa-caret-right"></i>
                            Tài Khoản Nhận Tiền:
                        </td>
                        <td>
                            <b><span id="wallet-current"><?= $jsonContent->numberCard ?></span></b>
                        </td>
                    </tr>
                <?php                                    }elseif($transactionDetail['payment_method'] === "NL"){
                    ?>
                    <tr>
                        <td>
                            <i class="fa fa-caret-right"></i>
                            Mã Ngân Hàng:
                        </td>
                        <td>
                            <b><span id="wallet-current"><?= $transactionDetail['payment_bank_code'] ?></span></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="fa fa-caret-right"></i>
                            Họ Và Tên Người Nhận:
                        </td>
                        <td>
                            <b><span id="wallet-current"><?= $jsonContent->cardholderName ?></span></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="fa fa-caret-right"></i>
                            Số Tài Khoản Ngân Hàng:
                        </td>
                        <td>
                            <b><span id="wallet-current"><?= $jsonContent->numberCard ?></span></b>
                        </td>
                    </tr>
                <?php }
            }
            ?>
            <tr>
                <td style="/* padding-top: 20px */">
                    <i class="fa fa-caret-right"></i>
                    Trạng Thái:
                </td>
                <td style="/* padding-top: 20px */">
                    <span class="label <?= WeshopHelper::getStatusTransactionLabel($transactionDetail['status']); ?>"><?= WeshopHelper::getStatusTransaction($transactionDetail['status']); ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <i class="fa fa-caret-right"></i>
                    t.g Cập Nhật Trạng Thái:
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
                    <a class="btn btn-outline-info" href="/my-weshop/wallet/history.html"> Trở lại </a>
                </td>
            </tr>
        </table>
        <?php }else{?>
            <strong style="color: red">Không tìm thấy giao dịch này.</strong>
        <?php } ?>
    </div>
</div>
