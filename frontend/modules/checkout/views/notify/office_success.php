<?php

use yii\helpers\Html; ?>

<div class="container">
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-danger"><strong>Thanh toán tại văn phòng</strong></h2>
            </div>
            <div class="panel-body">
                <span class="text-danger"><strong>MIỄN PHÍ</strong></span>, áp dụng tại văn phòng Weshop khu vực Hà Nội và TP. Hồ Chí Minh, thanh toán chọn 1 trong 2 địa chỉ sau:
                <br/>
                Bạn hãy chọn địa điểm để thanh toán:
                <ul>
                    <li>Hà Nội: Tầng 16, toà nhà VTC online, 18 Tam Trinh, P. Minh Khai, Q. Hai Bà Trưng</li>
                    <li>Tp.Hồ Chí Minh: Lầu 6, Toà nhà Sumikura, 18H Cộng Hoà, Phường 4, Quận Tân Bình, TP.HCM</li>
                </ul>
                <i>Bạn đến nộp tiền tại văn phòng của Weshop tại Hà Nội và TP. Hồ Chí Minh;  <span class="text-danger"><strong>buổi sáng từ 8h to 12h, buổi chiều: 13h30 to 17h30</strong></span> các ngày trong tuần (Thứ Bảy nghỉ buổi chiều), trừ ngày Lễ và Chủ nhật.</i>
            </div>
            <div class="panel-footer text-center">
                <?=Html::a("Tôi đã hiểu","/",["class" => "btn btn-default ws-btn"]);?>
            </div>
        </div>
    </div>
</div>>
