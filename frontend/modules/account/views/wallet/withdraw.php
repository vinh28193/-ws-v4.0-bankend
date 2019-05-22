<?php
/**
 * @var $wallet array
 */

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\ArrayHelper;

$listMethod = ArrayHelper::getValue(Yii::$app->params, 'list_method_withdraw');
$js = "
$(document).ready(function () {
//set default
        withdraw_data.method = '" . (isset($listMethod[0]) ? $listMethod[0] : '') . "';
    });";
$this->registerJs($js, \yii\web\View::POS_END);


$this->title = "Tạo yêu cầu rút tiền";
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => ['Rút tiền' => '/my-weshop/wallet/withdraw.html']]);
$this->params = ['wallet','withdraw'];
?>

<div class="be-box">
    <div class="be-top">
        <div class="title">Thông tin số dư tài khoản</div>
    </div>
    <div class="be-body be-withdraw">
        <div class="be-table">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Số dư tài khoản</th>
                    <th scope="col">Số dư đóng băng</th>
                    <th scope="col">Số dư khả dụng</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'current_balance')) ?></td>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'freeze_balance')) ?></td>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'usable_balance')) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <ul class="withdraw-step">
            <li class="active">
                <div class="step">1</div>
                <p>Tạo Yêu cầu rút</p>
            </li>
            <li class="step">
                <div class="step">2</div>
                <p>Xác nhận yêu cầu rút</p>
            </li>
            <li>
                <div class="step">3</div>
                <p>Yêu cầu rút thành công</p>
            </li>
        </ul>
        <div class="title-2">Qúy khách vui lòng chọn phương thức rút tiền</div>
        <div class="withdraw-box">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($listMethod as $k => $method) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= $k == 0 ? 'active' : '' ?>" data-toggle="tab" href="#withdraw-<?= $k ?>" onclick="changeMethod('<?= $method ?>')" role="tab"
                       aria-selected="true"><span><?php
                            switch ($method){
                                case 'NL':
                                    echo 'Rút tiền về ví Ngân Lượng';
                                    break;
                                case 'BANK':
                                    echo 'Rút tiền về tài khoản ngân hàng';
                                    break;
                            }
                            ?></span></a>
                </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($listMethod as $k => $method) {?>
                <div class="tab-pane fade <?= $k == 0 ? 'show active' : '' ?>" id="withdraw-<?= $k ?>" role="tabpanel">
                    <?= \frontend\modules\account\views\widgets\TabWithdrawWidget::widget(['method' => $method]) ?>
                </div>
                <?php } ?>
                <div class="form-group">
                    <div class="label">Số tiền cần rút</div>
                    <input type="number" name="amount" class="form-control" placeholder="Nhập số tiền cần rút">
                </div>
                <div class="form-group">
                    <div class="label">Số tiền cần rút</div>
                    <b id="amount">0 đ</b>
                </div>
                <div class="form-group">
                    <div class="label">Phí rút tiền</div>
                    <b id="fee">0 đ</b>
                </div>
                <div class="form-group">
                    <div class="label">Tổng số tiền rút</div>
                    <b class="text-orange" id="total_amout">0 đ</b>
                </div>
                <div class="form-group">
                    <div class="label">Xác nhận bằng mất khẩu</div>
                    <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu">
                </div>
                <div class="text-right">
                    <button type="button" name="submit_withdraw" class="btn btn-submit">Xác nhận</button>
                </div>
            </div>
        </div>
        <div class="be-notice">
            <div class="notice-title">Lưu ý:</div>
            - Tổng số tiền rút phải lớn hơn hoặc bằng 100.000đ và nhỏ hơn hoặc bằng Số dư khả dụng.<br/>
            - Phí rút tiền là: 3.000đ + 1%. Phí không vượt quá 10.000đ.<br/>
            - Quý khách lưu ý điền đúng thông tin tài khoản. Nếu quý khách điền sai thông tin mà Weshop đã thực hiện lệnh chuyển tiền thì quý khách sẽ phải chịu phí chuyển tiền của ngân hàng.
        </div>
    </div>
</div>
