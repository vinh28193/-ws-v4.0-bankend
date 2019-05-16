<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\PromotionUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotion Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-wallet">
    <div class="form-group form-inline code-form">
        <label>Thêm mã giảm giá:</label>
        <div class="input-group">
            <input type="text" class="form-control">
            <div class="input-group-append">
                <button class="btn btn-save" type="button">Lưu</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="voucher-box">
                <div class="vb-header">
                    <div class="title">Mã giảm giá từ Weshop</div>
                    <ul class="vb-filter">
                        <li><b class="text-blue">Có hiệu lực</b></li>
                        <li><a href="#">Đã sử dụng</a></li>
                        <li><a href="#">Không hợp lệ</a></li>
                    </ul>
                </div>
                <div class="vb-content">
                    <ul class="voucher-list">
                        <?php foreach ($models as $model) { ?>
                            <li>
                                <div class="thumb"><img src="./img/voucher1.jpg" alt=""/></div>
                                <div class="info">
                                    <div class="name">
                                        <b><?= $model->promotion->name ?></b>
                                        <a href="#" class="text-blue">Dùng ngay</a>
                                    </div>
                                    <p>Đơn Tối Thiểu 0đ, giảm tối đa 50k, Cho một số sản phẩm<br/>
                                        Mã: <b><?= $model->promotion->code ?></b></p>
                                    <div class="date">
                                        <span>HSD: 15.03.2019</span>
                                        <a href="#" class="text-blue">Chi tiết >></a>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="voucher-box">
                <div class="vb-header">
                    <div class="title">Mã giảm giá từ Weshop</div>
                    <ul class="vb-filter">
                        <li><b class="text-blue">Có hiệu lực</b></li>
                        <li><a href="#">Đã sử dụng</a></li>
                        <li><a href="#">Không hợp lệ</a></li>
                    </ul>
                </div>
                <div class="vb-content">
                    <ul class="voucher-list">
                        <li>
                            <div class="thumb"><img src="./img/voucher2.jpg" alt=""/></div>
                            <div class="info">
                                <div class="name">
                                    <b>Techcombank</b>
                                    <a href="#" class="text-blue">Dùng ngay</a>
                                </div>
                                <p>Đơn Tối Thiểu 0đ, giảm tối đa 50k, Cho một số sản phẩm<br/>
                                    Mã: <b>HAPPYBIRTHDAY18</b></p>
                                <div class="date">
                                    <span>HSD: 15.03.2019</span>
                                    <a href="#" class="text-blue">Chi tiết >></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="thumb"><img src="./img/voucher2.jpg" alt=""/></div>
                            <div class="info">
                                <div class="name">
                                    <b>Techcombank</b>
                                    <a href="#" class="text-blue">Dùng ngay</a>
                                </div>
                                <p>Đơn Tối Thiểu 0đ, giảm tối đa 50k, Cho một số sản phẩm<br/>
                                    Mã: <b>HAPPYBIRTHDAY18</b></p>
                                <div class="date">
                                    <span>HSD: 15.03.2019</span>
                                    <a href="#" class="text-blue">Chi tiết >></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
