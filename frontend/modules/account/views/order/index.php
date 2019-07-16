<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var \common\models\Order[] $models */

$this->title = Yii::t('frontend', 'Orders');
$check = Yii::$app->getRequest()->getQueryParams();
$checkUrl = Yii::$app->getRequest()->url;
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="tab-card-header">
            <ul class="nav nav-tabs card-header-tabs mb-1" id="myTab" role="tablist">
                <li class="nav-item ml-2  text-center" style="width: 10%">
                    <?php
                    if ($checkUrl == '/my-order.html') {
                        echo Html::a(Yii::t('frontend', 'All Orders List'), ['/my-order.html'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'All Orders List'), ['/my-order.html'], ['class' => 'nav-link']);
                    }
                    ?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php if ($checkUrl == '/my-order.html?p=2') {
                        echo Html::a(Yii::t('frontend', 'Unpaid'), ['/my-order.html?p=2'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'Unpaid'), ['/my-order.html?p=2'], ['class' => 'nav-link']);
                    }?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php if ($checkUrl == '/my-order.html?p=3') {
                        echo Html::a(Yii::t('frontend', 'Paid'), ['/my-order.html?p=3'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'Paid'), ['/my-order.html?p=3'], ['class' => 'nav-link']);
                    } ?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php if ($checkUrl == '/my-order.html?p=4') {
                        echo Html::a(Yii::t('frontend', 'Purchased'), ['/my-order.html?p=4'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'Purchased'), ['/my-order.html?p=4'], ['class' => 'nav-link']);
                    } ?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php if ($checkUrl == '/my-order.html?p=5') {
                        echo Html::a(Yii::t('frontend', 'Delivering'), ['/my-order.html?p=5'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'Delivering'), ['/my-order.html?p=5'], ['class' => 'nav-link']);
                    } ?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php if ($checkUrl == '/my-order.html?p=6') {
                        echo Html::a(Yii::t('frontend', 'Delivered'), ['/my-order.html?p=6'], ['class' => 'nav-link active']);
                    } else {
                        echo Html::a(Yii::t('frontend', 'Delivered'), ['/my-order.html?p=6'], ['class' => 'nav-link']);
                    } ?>
                </li>
                <li class="nav-item text-center" style="width: 10%">
                    <?php  if ($checkUrl == '/my-order.html?p=7') {
                        echo Html::a(Yii::t('frontend', 'Cancel'), ['/my-order.html?p=4'], ['class' => 'nav-link active']);
                    }
                    else {
                        echo Html::a(Yii::t('frontend', 'Cancel'), ['/my-order.html?p=4'], ['class' => 'nav-link']);
                    }?>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="be-order">
    <div class="be-table overflow-auto" style="max-height: 55em">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Mã đơn hàng</th>
                <th scope="col">Sản phẩm</th>
                <th scope="col">Tổng tiền</th>
                <th scope="col">Tracking</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($models as $order) {?>
                <tr style="border-bottom: 1px solid #ebebeb">
                    <td>
                        <?php if ($order->total_paid_amount_local > 0) { ?>
                            <a href="javascript:void(0)"><?= $order->ordercode ?></a>
                        <?php } else { ?>
                            <a href="<?= 'checkout.html?code=' . $order->ordercode ?>"
                               target="_blank"><?= $order->ordercode ?></a>
                            <?php } ?>
                        <b><?= $order->current_status ?></b>
                        <b style="color: darkgray"><?= Yii::$app->getFormatter()->asDatetime($order->created_at) ?></b>

                    </td>
                    <td>
                        <?php foreach ($order->products as $product) { ?>
                            <div class="product-info">
                                <div class="thumb">
                                    <a href="<?= $product->product_link ?>" target="_blank">
                                        <img src="<?= !is_null($product->link_img) ? $product->link_img : 'no-image' ?>"
                                             alt=""/>
                                    </a>
                                </div>
                                <div class="info">
                                    <a href="<?= 'my-order/'.$order->ordercode.'.html' ?>"
                                       target="_blank"><?= $product->product_name ?></a>
                                    <span><?= $product->quantity_purchase ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                    <td>
                        <b class="total text-orange"><?= number_format($order->total_final_amount_local, 0, ',', '.');?><?php if ($order->store_id == 1) {echo 'đ';} else {echo 'IDR';} ?></b>
                    </td>
                    <td><?= $order->tracking_codes ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
