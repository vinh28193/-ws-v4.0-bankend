<?php


?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li><i>1</i><span>Đăng nhập</span></li>
        <li><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li class="active"><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-1-content">
        <div class="title">Nhập số điện thoại/ Email để tiếp tục thanh toán</div>
        <form class="auth-form">
            <div class="form-group checkout-2-form">
                <input type="text" class="form-control" placeholder="Nhập số điện thoại hoặc email">
            </div>
            <div class="check-member">
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="member" name="check-member" checked>
                    <label class="form-check-label" for="member">Đã là thành viên Weshop</label>
                </div>
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="new-customer" name="check-member">
                    <label class="form-check-label" for="new-customer">Tôi là khách hàng mới</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login">Tiếp tục mua hàng</button>
        </form>
        <div class="other-login">
            <div class="text-center"><span class="or">Hoặc đăng nhâp qua</span></div>
            <div class="social-button">
                <a href="#" class="btn btn-fb">
                    <i class="social-icon fb"></i>
                    <span>Facebook</span>
                </a>
                <a href="#" class="btn btn-google">
                    <i class="social-icon google"></i>
                    <span>Google</span>
                </a>
            </div>
        </div>
    </div>
</div>
