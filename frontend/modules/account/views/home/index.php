<?php

/* @var $this \yii\web\View */
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon1"><i class="icon"></i></span>
            <div class="name">Số dư</div>
            <div class="info">50.800.000đ</div>
            <a href="#">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon2"><i class="icon"></i></span>
            <div class="name">Đơn hàng</div>
            <div class="info"><?= $total ?></div>
            <a href="#">Chi tiết >></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="statistic-item">
            <span class="icon-box icon3"><i class="icon"></i></span>
            <div class="name">Giỏ hàng</div>
            <div class="info">508</div>
            <a href="#">Chi tiết >></a>
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
                                    <td><a href="#"><?php echo Html::a($order->ordercode, ['/account/order/' . $order->id . '.html']); ?></a> </td>
                                    <td><?= $order->current_status ?></td>
                                    <td><?= Yii::$app->getFormatter()->asDatetime($order->updated_at) ?></td>
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
                            <p><b>Weshop</b> trao đổi mới trong đơn hàng <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><?= $order->ordercode ?></a> vào lúc <?= Yii::$app->getFormatter()->asDatetime($order->created_at) ?> </p>
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>