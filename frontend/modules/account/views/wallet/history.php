<?php

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\ArrayHelper;

/**
 * @var $wallet array
 * @var $trans array
 * @var $total int
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
$this->params = ['wallet', 'history'];
$this->title = Yii::t('frontend', 'Transaction history');
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => [Yii::t('frontend', 'Transaction') => 'my-weshop/wallet/history']]);
$get = Yii::$app->request->get();
$page = Yii::$app->request->get('page', 1);
$total_page = ceil($total / 20);
$url_page = function ($p) {
    $param = [explode('?', \yii\helpers\Url::current())[0]];
    $param = Yii::$app->request->get() ? array_merge($param, Yii::$app->request->get()) : $param;
    $param['page'] = $p;
//           $param['portal'] = $portal;
    return Yii::$app->getUrlManager()->createUrl($param);
};
?>
<div class="be-box">
    <div class="be-top">
        <div class="title"><?= Yii::t('frontend', 'Account balance information'); ?></div>
    </div>
    <div class="be-body account-info" style="min-height: auto">
        <div class="row">
            <div class="col-md-4">
                <div><?= Yii::t('frontend', 'Current balance'); ?></div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'current_balance')) ?></b>
            </div>
            <div class="col-md-4">
                <div><?= Yii::t('frontend', 'Freeze balance'); ?></div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'freeze_balance')) ?></b>
            </div>
            <div class="col-md-4">
                <div><?= Yii::t('frontend', 'Usable balance'); ?></div>
                <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'usable_balance')) ?></b>
            </div>
        </div>
    </div>
</div>
<div class="be-box">
    <div class="be-top">
        <div class="title"><?= Yii::t('frontend', 'Transaction information'); ?></div>
    </div>
    <div class="be-body be-deal">
        <form id="search_form" method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="transaction_code"
                               value="<?= ArrayHelper::getValue($get, 'transaction_code') ?>"
                               placeholder="<?= Yii::t('frontend', 'Transaction code') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="order_code"
                               value="<?= ArrayHelper::getValue($get, 'order_code') ?>"
                               placeholder="<?= Yii::t('frontend', 'Order code') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="transaction_type" class="form-control">
                            <option value=""><?= Yii::t('frontend', 'Transaction type') ?></option>
                            <option value="top_up" <?= ArrayHelper::getValue($get, 'transaction_type') === 'top_up' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Top up') ?>
                            </option>
                            <option value="pay_order" <?= ArrayHelper::getValue($get, 'transaction_type') === 'pay_order' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Pay order') ?>
                            </option>
                            <option value="with_draw" <?= ArrayHelper::getValue($get, 'transaction_type') === 'with_draw' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Withdraw') ?>
                            </option>
                            <option value="addfee" <?= ArrayHelper::getValue($get, 'transaction_type') === 'addfee' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Additional Fee') ?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" name="from_date"
                               value="<?= ArrayHelper::getValue($get, 'from_date') ?>" id="datepicker"
                               placeholder="<?= Yii::t('frontend', 'From date') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" name="to_date"
                               value="<?= ArrayHelper::getValue($get, 'to_date') ?>" id="datepicker2"
                               placeholder="<?= Yii::t('frontend', 'From date') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control" name="transaction_status">
                            <option value=""><?= Yii::t('frontend', 'Transaction status') ?></option>
                            <option value="0" <?= ArrayHelper::getValue($get, 'transaction_status') === '0' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Waiting') ?>
                            </option>
                            <option value="1" <?= ArrayHelper::getValue($get, 'transaction_status') === '1' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Processing') ?>
                            </option>
                            <option value="2" <?= ArrayHelper::getValue($get, 'transaction_status') === '2' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Success') ?>
                            </option>
                            <option value="3" <?= ArrayHelper::getValue($get, 'transaction_status') === '3' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Cancel') ?>
                            </option>
                            <option value="4" <?= ArrayHelper::getValue($get, 'transaction_status') === '4' ? 'selected' : '' ?>>
                                <?= Yii::t('frontend', 'Failed') ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-search">Tìm kiếm</button>
            </div>
        </form>
    </div>
</div>
<div class="be-box">
    <div class="be-top">
        <div class="text-left">
            <div class="title">Số giao dịch: <?= $total ?></div>
        </div>
        <div class="text-right">
            <ul class="pagination justify-content-center" style="margin: 0px">
                <?php
                $limitPage = 10;
                $arr = WeshopHelper::getArrayPage($total_page, $page, $limitPage);
                if ($arr && count($arr) > 1) {
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $page > 1 ? $url_page($page - 1) : 'javascript: void (0)' ?>"
                           tabindex="-1" aria-disabled="true"></a>
                    </li>
                    <?php
                    if ($arr[0] != 1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $url_page(1) . "'>1</a></li>";
                        echo "<li class='page-item'><span class='more'>...</span></li>";
                    }
                    foreach ($arr as $p) {
                        if ($p == $page) {
                            echo "<li class='page-item active' aria-current='page'>" .
                                "<a class='page-link' href='" . $url_page($p) . "'>" .
                                "" . $p . " <span class='sr-only'>(current)</span>" .
                                "</a>" .
                                "</li>";
                        } elseif ($p == $total_page) {
                            echo "<li class='page-item' aria-current='page'><a class='page-link last' href='" . $url_page($p) . "'>" . $p . "</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='" . $url_page($p) . "'>" . $p . "</a></li>";
                        }
                    }
                    if ($arr[count($arr) - 1] != $total_page) {
                        echo "<li class='page-item'><span class='more'>...</span></li>";
                        echo "<li class='page-item'><a class='page-link last' href='" . $url_page($total_page) . "'>" . $total_page . "</a></li>";
                    }
                    ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="<?= $page < $total_page ? $url_page($page + 1) : 'javascript: void (0)' ?>"></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
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
                        <td class="text-center"><b
                                    class="text-orange"><?= ArrayHelper::getValue($tran, 'credit_amount') ? '+' . WeshopHelper::showMoney(ArrayHelper::getValue($tran, 'credit_amount')) : ArrayHelper::getValue($tran, 'debit_amount') ?></b>
                        </td>
                        <td class="text-center">
                            <b><?= WeshopHelper::showMoney(ArrayHelper::getValue($tran, 'amount')) ?></b></td>
                        <td class="text-center"><?= WeshopHelper::getTypeTransaction(ArrayHelper::getValue($tran, 'type')) ?></td>
                        <td><?= ArrayHelper::getValue($tran, 'description') ?></td>
                        <td class="text-center"><span
                                    class="badge badge-<?= WeshopHelper::getStatusTransactionColor(ArrayHelper::getValue($tran, 'status')) ?>"><?= WeshopHelper::getStatusTransaction(ArrayHelper::getValue($tran, 'status')) ?></span>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>