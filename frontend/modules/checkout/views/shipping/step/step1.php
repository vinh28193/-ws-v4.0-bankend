<?php
?>

<div class="container checkout-content">
    <ul class="checkout-step">
        <li class="active"><i>1</i><span>Đăng nhập</span></li>
        <li><i>2</i><span>Địa chỉ nhận hàng</span></li>
        <li><i>3</i><span>Thanh toán</span></li>
    </ul>
    <div class="step-1-content">
        <div class="title">Nhập số điện thoại/ Email để tiếp tục thanh toán</div>
        <div class="auth-form">
            <div class="form-group">
                <i class="icon email"></i>
                <input type="text" class="form-control" name="email" placeholder="Email">
                <label data-href="error" style="color: red" id="email-error"></label>
            </div>
            <div class="check-member">
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="member" value="member" name="check-member" checked>
                    <label class="form-check-label" for="member">Đã là thành viên Weshop</label>
                </div>
                <div class="form-group form-check">
                    <input type="radio" class="form-check-input" id="new-member" value="new-member" name="check-member">
                    <label class="form-check-label" for="new-member">Tôi là khách hàng mới</label>
                </div>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control"  name="first_name" placeholder="Tên">
                <label data-href="error" style="color: red" id="first_name-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon user"></i>
                <input type="text" class="form-control"   name="last_name" placeholder="Họ">
                <label data-href="error" style="color: red" id="last_name-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon phone"></i>
                <input type="text" class="form-control " name="phone" placeholder="Số điện thoại">
                <label data-href="error" style="color: red" id="phone-error"></label>
            </div>
            <div class="form-group">
                <i class="icon password"></i>
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu">
                <label data-href="error" style="color: red" id="password-error"></label>
            </div>
            <div class="form-group" data-merge="signup-form" style="display: none">
                <i class="icon password"></i>
                <input type="password" class="form-control"  name="replacePassword" placeholder="Nhập lại mật khẩu">
                <label data-href="error" style="color: red" id="replacePassword-error"></label>
            </div>
            <div class="check-info">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="remember" checked>
                    <label class="form-check-label" for="remember">Ghi nhớ</label>
                </div>
                <a href="#" class="forgot">Quên mật khẩu?</a>
            </div>
            <button type="button" id="loginToCheckout" class="btn btn-login">Đăng nhập để mua hàng</button>
        </div>
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

