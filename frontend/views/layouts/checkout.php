<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $step integer */

$this->beginContent('@frontend/views/layouts/common.php');

$steps = [
    1 => 'Đăng nhập',
    2 => 'Địa chỉ nhận hàng',
    3 => 'Thanh toán'
];
?>
    <div class="container checkout-content">
        <ul class="checkout-step">
            <?php
            foreach ($steps as $i => $label) {
                echo Html::tag('li', '<i>' . $i . '</i><span>' . $label . '</span>', ['class' => ($step >= $i ? 'active' : '')]);
            }
            ?>
        </ul>
        <div class="step-<?=$step;?>-content">
            <div class="row">
                <?=$content;?>
            </div>
        </div>
    </div>
<?php
$this->endContent();
?>