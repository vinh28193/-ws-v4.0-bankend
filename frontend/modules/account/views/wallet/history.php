<?php

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\ArrayHelper;

/**
 * @var $wallet array
 * @var $trans array
 */
$js = "
$('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        showRightIcon: false,
        format: 'dd/mm/yyyy'
    });
    $('#datepicker2').datepicker({
        uiLibrary: 'bootstrap4',
        showRightIcon: false,
        format: 'dd/mm/yyyy'
    });
";
$this->registerJs($js, \yii\web\View::POS_END);
$this->title = 'Lịch sử giao dịch';
echo HeaderContentWidget::widget(['title' => 'Lịch sử giao dịch','stepUrl' => ['Giao dịch' => 'my-weshop/wallet/history']]);
?>
<div class="be-box">
    <div class="be-top">
        <div class="title">Thông tin số dư tài khoản</div>
    </div>
    <div class="be-body account-info" style="min-height: auto">
        <div class="row">
            <div class="col-md-4">
                <div>Số dư tài khoản</div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'current_balance')) ?></b>
            </div>
            <div class="col-md-4">
                <div>Số dư đóng băng</div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'freeze_balance')) ?></b>
            </div>
            <div class="col-md-4">
                <div>Số dư khả dụng</div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'usable_balance')) ?></b>
            </div>
        </div>
    </div>
</div>
<div class="be-box">
    <div class="be-top">
        <div class="title">Thông tin giao dịch</div>
    </div>
    <div class="be-body be-deal">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Mã giao dịch">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Mã đơn hàng">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Loại giao dịch">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control datepicker" id="datepicker" placeholder="Từ ngày">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control datepicker" id="datepicker2" placeholder="Đến ngày">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Trạng thái">
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="button" class="btn btn-search">Tìm kiếm</button>
        </div>
    </div>
</div>
<div class="be-box">
    <div class="be-top">
        <div class="title">Số giao dịch: <?= count($trans) ?></div>
    </div>
    <div class="be-body be-deal2">
        <div class="be-table">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Mã giao dịch</th>
                    <th scope="col" class="text-center">Số tiền</th>
                    <th scope="col" class="text-center">Số dư sau giao dịch</th>
                    <th scope="col" class="text-center">Loại giao dịch</th>
                    <th scope="col" class="text-center">Nội dung</th>
                    <th scope="col" class="text-center">Trạng thái</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($trans as $tran) { ?>
                    <tr>
                        <td class="text-center">
                            <a href="/my-weshop/wallet/transaction/<?= ArrayHelper::getValue($tran, 'wallet_transaction_code') ?>/detail.html">
                                <?= ArrayHelper::getValue($tran, 'wallet_transaction_code') ?>
                            </a>
                            <div><?= ArrayHelper::getValue($tran, 'create_at') ?></div>
                        </td>
                        <td class="text-center"><b class="text-orange"><?= ArrayHelper::getValue($tran, 'credit_amount') ? '+' . WeshopHelper::showMoney(ArrayHelper::getValue($tran, 'credit_amount')) : ArrayHelper::getValue($tran, 'debit_amount') ?></b></td>
                        <td class="text-center"><b><?= WeshopHelper::showMoney(ArrayHelper::getValue($tran, 'amount')) ?></b></td>
                        <td class="text-center"><?= WeshopHelper::getTypeTransaction(ArrayHelper::getValue($tran, 'type')) ?></td>
                        <td><?= ArrayHelper::getValue($tran, 'description') ?></td>
                        <td class="text-center"><span class="label <?= WeshopHelper::getStatusTransactionLabel(ArrayHelper::getValue($tran, 'status')) ?>"><?= WeshopHelper::getStatusTransaction(ArrayHelper::getValue($tran, 'status')) ?></span></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>