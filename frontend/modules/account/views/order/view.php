<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->ordercode;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Orders'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$js = <<<JS
    $('a[data-popup=modal]').on('click',function(event) {
        event.preventDefault();
        var uri = $(this).data('url');
        $('div#exampleModalChat').modal('show').find('#modalContent').load(uri);
    });
JS;
$this->registerJs($js);
?>
<div class="order-detail">
    <ul class="od-header">
        <li><a href="/" class="icon icon1">Xem shop</a></li>
        <li>
            <style>
                a {
                    color: #555555;
                }
            </style>
            <?php
            echo Html::a('<span class="icon icon2"></span>Chat',new \yii\web\JsExpression('javascript:void(0);'),[
                'data-url' => \yii\helpers\Url::toRoute(['/account/chat/order-chat','code' => $model->ordercode],true),
                'data-target' => '#exampleModalChat',
                'data-popup' => 'modal'
            ])
            ?>
<!--            <a href="#" class="icon icon2">Chat</a>-->
        </li>
        <li><a href="#" class="icon icon3">Khiếu nại</a></li>
    </ul>
    <?php
    $status = array('NEW', 'AWAITING_CONFIRM_PURCHASE', 'PURCHASED', 'DELIVERING', 'DELIVERED');
    $count = 0;
    foreach ($status as $key => $sta) {
        unset($status[$key]);
        if ($sta == $model->current_status) {
            break;
        }
    }
    ?>
    <div class="od-content">
        <div class="title"><?= Yii::t('frontend', 'Order tracking'); ?></div>
        <ul class="od-tracking">
            <li class="<?php if (!in_array('NEW', $status)) { ?> active <?php } ?>">
                <i class="icon icon1"></i>
                <span><?= Yii::t('frontend', 'Purchasing'); ?></span>
            </li>
            <li class="<?php if (!in_array('AWAITING_CONFIRM_PURCHASE', $status)) { ?> active <?php } ?>">
                <i class="icon icon2"></i>
                <span><?= Yii::t('frontend', 'Awaiting confirm purchase'); ?></span>
            </li>
            <li class="<?php if (!in_array('PURCHASED', $status)) { ?> active <?php } ?>">
                <i class="icon icon3"></i>
                <span><?= Yii::t('frontend', 'Purchased'); ?></span>
            </li>
            <li class="<?php if (!in_array('DELIVERING', $status)) { ?> active <?php } ?>">
                <i class="icon icon4"></i>
                <span><?= Yii::t('frontend', 'Delivering'); ?></span>
            </li>
            <li class="<?php if (!in_array('DELIVERED', $status)) { ?> active <?php } ?>">
                <i class="icon icon5"></i>
                <span><?= Yii::t('frontend', 'Delivered'); ?></span>
            </li>
        </ul>
        <div class="row info-detail">
            <div class="col-md-4">
                <div class="title-2"><?= Yii::t('frontend', 'Receiver address'); ?></div>
                <ul>
                    <li><?= Yii::t('frontend', 'Name: {name}', ['name' => $model->receiver_name]); ?></li>
                    <li><?= Yii::t('frontend', 'Address: {address}', ['address' => $model->receiver_address]); ?></li>
                    <li><?= Yii::t('frontend', 'Phone: {phone}', ['phone' => $model->receiver_phone]); ?></li>
                    <li><?= Yii::t('frontend', 'Email: {email}', ['email' => $model->receiver_email]); ?></li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="title-2"><?= Yii::t('frontend', 'Method Of Payment'); ?></div>
                <div><?= $model->payment_type ?></div>
            </div>
            <div class="col-md-4">
                <div class="title-2"><?= Yii::t('frontend', 'Total amount'); ?></div>
                <b class="text-orange"><?= number_format($model->total_final_amount_local, 2, ',', '.'); ?><?php if ($model->store_id == 1) {echo 'VNĐ';} else {echo 'IDR';} ?></b>
                <div class="title-2"><?= Yii::t('frontend', 'Total paid amount'); ?></div>
                <b class="text-orange"><?= number_format($model->total_paid_amount_local, 2, ',', '.'); ?><?php if ($model->store_id == 1) {echo 'VNĐ';} else {echo 'IDR';} ?></b>
            </div>
        </div>
        <div class="od-table">
            <div class="be-table">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"><?= Yii::t('frontend', 'Product'); ?></th>
                        <th scope="col"><?= Yii::t('frontend', 'Quantity'); ?></th>
                        <th scope="col"><?= Yii::t('frontend', 'Price'); ?></th>
                        <th scope="col"><?= Yii::t('frontend', 'Status'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($model->products as $product) {
                        ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="thumb">
                                        <img src="<?= $product->link_img ?>" alt=""/>
                                    </div>
                                    <div class="info"><?= $product->product_name ?></div>
                                </div>
                            </td>
                            <td>01</td>
                            <td><b class="total text-orange"><?= number_format($product->total_price_amount_local) ?><?php if ($model->store_id == 1) {echo 'VNĐ';} else {echo 'IDR';} ?></b></td>
                            <td><b><?= $product->current_status ?></b></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--        <div class="package form-inline">-->
        <!--            <div class="form-check">-->
        <!--                <input class="form-check-input" type="radio" name="package" id="package1" checked>-->
        <!--                <label class="form-check-label" for="package1">Yêu cầu đóng thùng tại Mỹ</label>-->
        <!--            </div>-->
        <!--            <div class="form-check">-->
        <!--                <input class="form-check-input" type="radio" name="package" id="package2">-->
        <!--                <label class="form-check-label" for="package2">Yêu cầu đóng thùng tại Việt Nam</label>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="title-2">Thông tin kiện hàng</div>-->
        <!--        <div class="package-table">-->
        <!--            <div class="be-table">-->
        <!--                <table class="table text-center">-->
        <!--                    <thead>-->
        <!--                    <tr>-->
        <!--                        <th scope="col">Kiện hàng</th>-->
        <!--                        <th scope="col">Trạng thái kiện</th>-->
        <!--                        <th scope="col">Mã Boxme</th>-->
        <!--                    </tr>-->
        <!--                    </thead>-->
        <!--                    <tbody>-->
        <!--                    <tr>-->
        <!--                        <td>1 kiện</td>-->
        <!--                        <td>abc ...</td>-->
        <!--                        <td><a href="#" class="text-blue">Chi tiết >></a></td>-->
        <!--                    </tr>-->
        <!--                    <tr>-->
        <!--                        <td>1 kiện</td>-->
        <!--                        <td>abc ...</td>-->
        <!--                        <td><a href="#" class="text-blue">Chi tiết >></a></td>-->
        <!--                    </tr>-->
        <!--                    <tr>-->
        <!--                        <td>1 kiện</td>-->
        <!--                        <td>abc ...</td>-->
        <!--                        <td><a href="#" class="text-blue">Chi tiết >></a></td>-->
        <!--                    </tr>-->
        <!--                    </tbody>-->
        <!--                </table>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</div>

<div class="modal" id="exampleModalChat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="card m-b-0">
                        <div class="card-body">
                            <div id="modalContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
