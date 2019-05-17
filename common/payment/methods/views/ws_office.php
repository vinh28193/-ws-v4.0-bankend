<?php

/* @var yii\web\View $this */
/* @var integer $group */
/* @var common\payment\Payment $payment */
/* @var array $methods */
/* @var boolean $selected */
?>

<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?=$group;?>" aria-expanded="<?=$selected ? 'true' : 'false';?>" onclick="ws.payment.selectMethod(<?=$methods[0]['payment_provider_id']?>,<?=$methods[0]['payment_method_id']?>, '<?=$methods[0]['paymentMethod']['code'];?>')">
        <i class="icon method_<?=$group;?>"></i>
        <div class="name">Thanh toán tại văn phòng</div>
        <div class="desc">Qúy khách vui lòng chọn địa điểm để thanh toán</div>
    </a>

    <div id="method<?=$group;?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content office">
            <p><b>MIỄN PHÍ,</b> áp dụng tại văn phòng Weshop khu vực Hà Nội và TP. Hồ Chí Minh, thanh toán chọn 1 trong 2 địa chỉ sau:</p>

            <div class="address">
                <b>Hà Nội:</b> Tầng 16, toà nhà VTC online, 18 Tam Trinh, P. Minh Khai, Q. Hai Bà Trưng<br/>
                <b>Tp.Hồ Chí Minh:</b> Lầu 6, Toà nhà Sumikura, 18H Cộng Hoà, Phường 4, Quận Tân Bình, TP.HCM
            </div>

            <p>Bạn đến nộp tiền tại văn phòng của Weshop tại Hà Nội và TP. Hồ Chí Minh; buổi sáng từ <b>8h to 12h</b>, buổi chiều: <b>13h30 to 17h30</b> các ngày trong tuần (Thứ Bảy nghỉ buổi chiều), trừ ngày Lễ và Chủ nhật.</p>
        </div>
    </div>
</div>