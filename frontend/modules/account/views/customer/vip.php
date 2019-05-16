<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 5/10/2019
 * Time: 3:08 PM
 */
use yii\helpers\Html;
?>
<div class="vip-level">
    <div class="vl_block1">
        <div class="vip-info">
            <span class="icon-money"></span>
            <span>Vip 1</span>
            <?php $total = 0; foreach ($models as $model) {
                $total += $model->total_paid_amount_local;
            };
            $percent = ($total/100000000)*100;
            ?>
            <b><?= number_format($total, 2, ',', '.').'đ' ?></b>
            <div class="progress" style="margin-bottom: 0">
                <span class="progress-bar" style="width: <?php if ($percent > 100) {
                    echo '100';
                } else {echo $percent;}?>%; background-color: orange; margin: 0" aria-valuemax="100" aria-valuenow="75"><?= round($percent) ?>%</span>
            </div>
            <div class="text-right vl-price">VIP 1: 100.000.000đ</div>
        </div>
        <div class="title">Thông tin tích lũy</div>
        <div class="be-table">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col" class="text-center">Cấp hiện tại</th>
                    <th scope="col" class="text-center">Cấp tiếp theo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Cấp độ</td>
                    <td class="text-center"><b>Vip 0</b></td>
                    <td class="text-center"><b>Vip 1</b></td>
                </tr>
                <tr>
                    <td>Chiết khấu mua hàng</td>
                    <td class="text-center"><b>- 0%</b></td>
                    <td class="text-center"><b>- 5%</b></td>
                </tr>
                <tr>
                    <td>Chiết khấu vận chuyển US - VN</td>
                    <td class="text-center"><b>- 0%</b></td>
                    <td class="text-center"><b>- 2%</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="vl_block2">
        <div class="title">Điểm tích lũy gần đây</div>
        <ul class="point-list">
            <?php foreach ($models as $model) { ?>
                <li>
                    <?php foreach ($model->products as $product) { ?>
                    <div class="thumb">
                        <img src="<?= $product->link_img ?>" alt=""/>
                    </div></br>
                    <?php } ?>
                    <div class="info">
                        <div class="detail">
                            <?php echo Html::a($model->ordercode, ['/order/' . $model->id]); ?>
                            <span>08/04/2019</span>
                        </div>
                        <div class="price">+<?= number_format($model->total_paid_amount_local, 2, ',', '.').'đ' ?></div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
