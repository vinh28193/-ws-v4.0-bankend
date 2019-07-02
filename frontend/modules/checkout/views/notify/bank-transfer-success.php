<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $code string */
/* @var $token string */
/* @var $amount string */
/* @var $orders string */
/** @var  $storeManager \common\components\StoreManager */
$storeManager = Yii::$app->storeManager;
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-checkout">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-title">
                                Thanh toán bằng hình thức chuyển khoản Ngân hàng
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <span class="text-danger mb-3"><strong>MIỄN PHÍ</strong></span>, quý khách vui lòng chuyển <?php if(isset($amount)):?><span class="text-danger"><?=$storeManager->showMoney($amount);?></span> <?php else: ?> tiền <?php endif;?> tới tài khoản:
                            <p class="mt-2">Số tài khoản: <b style="font-weight: 800">0451000277143</b></p>
                            <p class="">Chủ tài khoản: <b>Nguyễn Thị Hằng</b></p>
                            <p class="">Ngân hàng: <b>Vietcombank (Ngân hàng thương mại cổ phần Ngoại thương Việt Nam) </b> chi nhánh Thành Công</p>
                            <?php if(isset($orders)):?>
                                <p class="">Nội dung chuyển khoản cần ghi rõ: <span class="banking-content text-danger"><b style="font-weight: 800">Weshop <?=$orders?></b></p>
                            <?php else:?>
                                <p class="">Nội dung chuyển khoản cần ghi rõ: <span class="banking-content text-blue"><b>Weshop</b> Số điện thoại của bạn</span></p>
                                <p class="">Ví dụ: Weshop 0988380918</p>
                            <?php endif;?>
                            <p class="note"><span class="text-orange">(*)</span> Với những giao dịch nạp tiền chuyển khoản ngoài giờ hành chính. Thời gian hệ thống nhận được sẽ chậm hơn bình thường. Mong quý khách thông cảm!</p>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <img style="width: 200px" src="/images/0451000277143.jpg" alt="0451000277143 Nguyễn Thị Hằng Vietcombank">
                            <p class="mb-1"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>