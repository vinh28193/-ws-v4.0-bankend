<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\PromotionUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotion Users';
$this->params['breadcrumbs'][] = $this->title;
$checkUrl = Yii::$app->getRequest()->url;
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
                    <div class="mt-3 tab-card" style="width: 100%">
                        <div class="tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs ml-0 mb-1" id="myTab" role="tablist">
                                <li class="nav-item pt-2">
                                    <span style="font-weight: 500">Mã giảm giá từ Weshop</span>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Có hiệu lực</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Đã sử dụng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="three-tab" data-toggle="tab" href="#three" role="tab" aria-controls="Three" aria-selected="false">Không hợp lệ</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active pt-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                                <div class="vb-content">
                                    <ul class="voucher-list">
                                        <?php foreach ($models as $model) { ?>
                                            <li>
                                                <div class="thumb"><img src="../img/voucher1.jpg" alt=""/></div>
                                                <div class="info">
                                                    <div class="name">
                                                        <b><?= $model->promotion->name ?></b>
                                                        <a href="#" class="text-blue">Dùng ngay</a>
                                                    </div>
                                                    <p>Đơn Tối Thiểu 0đ, giảm tối đa <?= number_format($model->promotion->discount_max_amount) ?><?php if ($model->promotion->discount_type == 1) { ?> đ <?php } elseif ($model->promotion->discount_type == 2) {?> kg <?php } ?>, Cho một số sản phẩm<br/>
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
                            <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
                                <h5 class="card-title">Tab Card Two</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                            <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
                                <h5 class="card-title">Tab Card Three</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="voucher-box">
                <div class="vb-header">
                    <div class="mt-3 tab-card" style="width: 100%">
                        <div class=" tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs mb-1 ml-0" id="myTab" role="tablist">
                                <li class="nav-item pt-2">
                                    <span style="font-weight: 500">Mã giảm giá từ đối tác</span>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="nav-link active" id="one-tab" data-toggle="tab" href="#four" role="tab" aria-controls="four" aria-selected="true">Có hiệu lực</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="two-tab" data-toggle="tab" href="#five" role="tab" aria-controls="five" aria-selected="false">Đã sử dụng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="three-tab" data-toggle="tab" href="#six" role="tab" aria-controls="six" aria-selected="false">Không hợp lệ</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active pt-3" id="four" role="tabpanel" aria-labelledby="four-tab">
                                <div class="vb-content">
                                    <ul class="voucher-list">
                                        <?php foreach ($models as $model) { ?>
                                            <li>
                                                <div class="thumb"><img src="../img/voucher2.jpg" alt=""/></div>
                                                <div class="info">
                                                    <div class="name">
                                                        <b><?= $model->promotion->name ?></b>
                                                        <a href="#" class="text-blue">Dùng ngay</a>
                                                    </div>
                                                    <p>Đơn Tối Thiểu 0đ, giảm tối đa <?= number_format($model->promotion->discount_max_amount) ?><?php if ($model->promotion->discount_type == 1) { ?> đ <?php } elseif ($model->promotion->discount_type == 2) {?> kg <?php } ?>, Cho một số sản phẩm<br/>
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
                            <div class="tab-pane fade p-3" id="five" role="tabpanel" aria-labelledby="five-tab">
                                <h5 class="card-title">Tab Card Two</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                            <div class="tab-pane fade p-3" id="six" role="tabpanel" aria-labelledby="six-tab">
                                <h5 class="card-title">Tab Card Three</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
