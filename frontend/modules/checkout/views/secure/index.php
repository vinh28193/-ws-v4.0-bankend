<?php


?>
<div class="col-md-12">
    <div class="title">Nhập số điện thoại/ Email để tiếp tục thanh toán</div>
    <form class="auth-form">
        <div class="form-group">
            <i class="icon user"></i>
            <input type="text" class="form-control" placeholder="Email hoặc số điện thoại">
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
        <div class="form-group">
            <i class="icon password"></i>
            <input type="password" class="form-control" placeholder="Mật khẩu">
        </div>
        <div class="check-info">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Ghi nhớ</label>
            </div>
            <a href="#" class="forgot">Quên mật khẩu?</a>
        </div>
        <button type="submit" class="btn btn-login">Đăng nhập để mua hàng</button>
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

