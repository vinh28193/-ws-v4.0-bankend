<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="be-order">
    <div class="be-table">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Sản phẩm</th>
                <th scope="col">Mã đơn hàng</th>
                <th scope="col">Ngày mua</th>
                <th scope="col">Tổng tiền</th>
                <th scope="col">Trang thái đơn hàng</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($models  as $order) { ?>
            <tr style="border-bottom: 1px solid #ebebeb">
                <td>
                    <?php foreach ($order->products as $product) {?>
                        <div class="product-info">
                            <div class="thumb">
                                <img src="<?= !is_null($product->link_img) ? $product->link_img : 'no-image' ?>" alt=""/>
                            </div>
                            <div class="info">
                                <b><?= $product->product_name ?></b>
                                <span><?= $product->quantity_purchase ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </td>
                <td><a href="#"><?= $order->ordercode ?></a></td>
                <td>
                    <b><?= $order->new ?></b>
                </td>
                <td><b class="total text-orange"></b></td>
                <td><b><?= $order->current_status ?></b></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
