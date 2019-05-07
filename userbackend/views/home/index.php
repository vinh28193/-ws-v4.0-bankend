<?php

/* @var $this \yii\web\View */


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
            <div class="be-body">
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
                            <td><a href="#"><?= $order->ordercode ?></a> </td>
                            <td>Kho Việt Nam</td>
                            <td>17:34 04/04/2019</td>
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
            <div class="be-body">
                <ul class="new-update">
                    <li>
                        <p><b>Weshop</b> trao đổi mới trong đơn hàng <a href="#">CP77968802</a> vào lúc 10:47 23/01/2019</p>
                        <div class="mess-content">
                            <i class="logo"><img src="./img/weshop_small_logo.png" alt=""/></i>
                            <span>Bạn gì ơi có online không tớ nhờ tý</span>
                        </div>
                    </li>
                    <li>
                        <p><b>Weshop</b> trao đổi mới trong đơn hàng <a href="#">CP77968802</a> vào lúc 10:47 23/01/2019</p>
                        <div class="mess-content">
                            <i class="logo"><img src="./img/weshop_small_logo.png" alt=""/></i>
                            <span>Bạn gì ơi có online không tớ nhờ tý</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>