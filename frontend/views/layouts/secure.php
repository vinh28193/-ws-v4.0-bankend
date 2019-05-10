<?php

/* @var yii\web\View $this */
/* @var common\components\StoreManager $storeManager */
/* @var string $content */
$this->beginContent('@frontend/views/layouts/common.php');
?>

<div class="container">
    <div class="auth-content">
        <div class="logo">
            <img src="/img/weshop-logo-vn.png" alt="" title=""/>
        </div>
        <div class="auth-box">
            <div class="left">
                <?=$content;?>
            </div>
            <div class="right">
                <h2>Quyền Lợi Thành Viên</h2>
                <ul>
                    <li>Mua sắm đơn giản, nhanh chóng</li>
                    <li>Theo dõi đơn hàng dễ dàng</li>
                    <li>Giá ưu đãi cho thành viên</li>
                    <li>Nhận ưu đãi hấp dẫn trên khắp thế giới</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>