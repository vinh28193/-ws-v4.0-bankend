<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel userbackend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var \common\models\Order[] $models */

$this->title = Yii::t('frontend', 'Orders');
//$this->params['breadcrumbs'][] = $this->title;

?>
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
            <?php foreach ($models as $order) { var_dump($order); die()?>
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
                                    <img src="<?= !is_null($product->link_img) ? $product->link_img : 'no-image' ?>"
                                         alt=""/>
                                </div>
                                <div class="info">
                                    <b><?= $product->product_name ?></b>
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
