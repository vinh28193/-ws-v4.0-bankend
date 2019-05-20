<?php
/**
 * @var $this \yii\web\View
 * @var $wallet array
 * @var $totalCart int
 * @var $total int
 */

use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\helpers\Html;
$chat = false;
$this->title = 'My WeShop';
echo HeaderContentWidget::widget(['title' => 'Thống kê chung']);
?>
<div class="row">
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon1"><i class="icon"></i></span>
            <div class="name">Số dư</div>
            <?php if($wallet){
                echo '<div class="info">'.\common\helpers\WeshopHelper::showMoney($wallet['current_balance']).'</div>';
            }else{
                echo "<div><a href='javascript: void(0);' onclick=\"$('#loginWallet').modal()\">Nhấp vào đây</a> <i class='fas fa-question-circle' data-toggle='tooltip' title='Vui lòng xác thực lại mật khẩu để xem thông tin.'></i></div>";
            }?>
            <a href="/my-weshop/wallet/history.html">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon2"><i class="icon"></i></span>
            <div class="name">Đơn hàng</div>
            <div class="info"><?= $total ?></div>
            <?php echo Html::a('Chi tiết>>', ['/account/order'],['class' => 'active']); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon3"><i class="icon"></i></span>
            <div class="name">Giỏ hàng</div>
            <div class="info"><?= $totalCart ?></div>
            <a href="/my-cart.html">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon4"><i class="icon"></i></span>
            <div class="name">Khiếu nại</div>
            <div class="info">+ 50K</div>
            <a href="#">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="be-box">
            <div class="be-top">
                <div class="title">Thông tin đơn hàng mới cập nhập</div>
                <div class="top-action">
                    <a href="#">-</a>
                    <a href="#">x</a>
                </div>
            </div>
            <div class="be-body style-croll pb-1" style="height: 15em; overflow-y: scroll;" >
                <div class="be-table table-scrollbar">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Mã đơn hàng</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) {
                            foreach ($orders as $order) { ?>
                                <tr>
                                    <td><a href="<?= '/my-weshop/order/' . $order->ordercode . '.html' ?>"><?= $order->ordercode ?></a> </td>
                                    <td><?= $order->current_status ?></td>
                                    <td>
                                        <?php
                                            if ($order->current_status == 'NEW') {
                                                echo Yii::$app->getFormatter()->asDatetime($order->created_at);
                                            } else {
                                                echo Yii::$app->getFormatter()->asDatetime($order->updated_at);
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php }} else {?>
                            <tr>
                                <td colspan="3">
                                    <div class="no-data text-orange text-center">Chưa có đơn hàng nào!</div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="be-box">
            <div class="be-top">
                <div class="title">Trao đổi thông tin mới</div>
                <div class="top-action">
                    <a href="#">-</a>
                    <a href="#">x</a>
                </div>
            </div>
            <div class="be-body style-croll" style="height: 15em; overflow-y: scroll;">
                <ul class="new-update">
                    <?php foreach ($orders as $order) {
                        $chat = \common\modelsMongo\ChatMongoWs::find()->where([
                                'and',
                            ['Order_path' => $order->ordercode],
                            ['type_chat' => 'WS_CUSTOMER']
                        ])->orderBy(['created_at' => SORT_DESC])->one();
                        ?>
                        <li>
                            <p><b>Weshop</b> trao đổi mới trong đơn hàng <a href="#" data-toggle="modal" data-url="/echo?ordercode=<?=$order->ordercode  ?>" data-target="#exampleModalCenter"><?= $order->ordercode ?></a> vào lúc <?= Yii::$app->getFormatter()->asDatetime($order->created_at, "php:d-m-Y  H:i:s") ?> </p>
                            <?php if ($chat) {  ?>
                            <div class="mess-content mb-1">
                                <i class="logo"><img src="../img/weshop_small_logo.png" alt=""/></i>
                                <span><?= $chat->message ?></span>
                            </div>
                            <?php } ?>
                        </li>
                    <?php } ?>
                    <?php if (!$chat) { ?>
                        <div class="no-data text-orange text-center pt-5">Chưa có thông tin mới</div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="card m-b-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 p-0">
                                    <div class="modal-body p-0">
                                        <div class="ng-star-inserted">
                                            <div class="col-md-12 m-0 col-xl-12 chat">
                                                <div class="card m-0 p-0">
                                                    <div class="card-header msg_head bg-info">
                                                        <div class="d-flex bd-highlight">
                                                            <h3 class="text-white">Trao đổi với nhân viên (12345)</h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-body msg_card_body" #scrollMe [scrollTop]="scrollMe.scrollHeight">
                                                        <div>
                                                            <div>
                                                                <div class="d-flex justify-content-start mb-4">
                                                                    <div class="img_cont_msg">
                                                                        <img src="../img/weshop_small_logo.png"
                                                                             class="rounded-circle user_img_msg"  width="54px" height="15px">
                                                                    </div>
                                                                    <div>
                                                                        <div class="">
                                                                            <span class="mr-2">weshop</span>
                                                                            <p  class="text-darkgray">12345</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="card-body">
                                                            <div class="input-group">
            <textarea name="" rows="4" class="form-control type_msg"
                      placeholder="Nhập gửi nội dung trao đổi.
Nhấn Shift + Enter để xuống dòng.
Enter để gửi"></textarea>
                                                                <div class="input-group-btn">
                                                                    <button style="height: 94px;" class="btn btn-default pl-5 pr-5">Sent</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right mt-2">
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal" >Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>