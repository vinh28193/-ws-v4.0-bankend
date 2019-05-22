<?php
/**
 * @var $wallet array
 * @var $transaction_info array
 * @var $transaction_code string
 *
 */

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\ArrayHelper;

$listMethod = ArrayHelper::getValue(Yii::$app->params, 'list_method_withdraw');
$js = <<< JS
 $(document).ready(function () {
    var countDown = Number($('#seconds').html());
    var seconds = 0;
    var x = setInterval(function() {
        seconds ++;
        $('#seconds').html((countDown - seconds).toString())
  if (seconds >= countDown) {
        clearInterval(x);
         $('#message_loading').html('Đang chuyển hướng ...');
         ws.loading(true);
         location.assign('/my-weshop/wallet/history.html');
  }
}, 1000);
    });
JS;
$this->registerJs($js, \yii\web\View::POS_END);


$this->title = "Xác thực yêu cầu rút tiền";
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => ['Rút tiền' => '/my-weshop/wallet/withdraw.html']]);
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
            <li class="done">
                <div class="step">1</div>
                <p>Tạo Yêu cầu rút</p>
            </li>
            <li class="done">
                <div class="step">2</div>
                <p>Xác nhận yêu cầu rút</p>
            </li>
            <li class="active">
                <div class="step">3</div>
                <p>Yêu cầu rút thành công</p>
            </li>
        </ul>
        <div class="title-2">Xác thực giao dịch rút tiền #<b><?= $transaction_code ?></b></div>
        <div class="withdraw-done">
            <div class="done-notice">
                Bạn vừa thực hiện thành công lệnh rút tiền. Nhân viên
                <img src="/img/logo_ws.png"/>
                sẽ liên lạc với bạn sớm nhất để hoàn thành giao dịch rút tiền.
            </div>
            <p>Nếu cần trợ giúp vui lòng liên hệ : Hotline: <b class="text-orange">1900.67.55</b>   |  Email: <a href="#" class="text-blue">support@weshop.com.vn</a></p>
            <div class="loading-box">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="message_loading">Sau <span id="seconds">15</span> giây sẽ tự động chuyển về trang <a href="/my-weshop/wallet/history.html" class="text-blue">Lịch sử giao dịch</a></span>
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
