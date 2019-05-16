<?php


?>

<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method6" aria-expanded="false">
        <i class="icon method_6"></i>
        <div class="name">Weshop - Ewallet</div>
        <div class="desc">Qúy khách vui lòng chọn xác nhận mật khẩu</div>
    </a>

    <div id="method6" class="collapse" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content wallet">
            <button type="button" class="btn btn-add-credit" data-toggle="modal" data-target="#otp-confirm"><img src="./img/payment_wallet.png"/><span>Nạp tiền</span></button>
            <div class="row">
                <div class="col-md-6">
                    <label>Tổng số tiền chính trong tài khoản:</label>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" value="50.000.000 VNĐ" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Số tiền đóng băng: </label>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" value="3.000.000 VNĐ" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Số tiền có thể mua sắm:</label>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" value="47.000.000 VNĐ" disabled>
                    </div>
                </div>
                <div class="col-md-12">
                    <small class="warning text-orange">Tài khoản của bạn không đủ tiền. Vui lòng nạp tiền để tiến hành sử dụng</small>
                </div>
                <div class="col-md-6">
                    <b>Chọn hình thức nhận OTP:</b>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="wallet" id="wallet1" checked>
                        <label class="form-check-label" for="wallet1">Số điện thoại (0902524586)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="wallet" id="wallet2">
                        <label class="form-check-label" for="wallet2">Số E-mail (minhtuong.@gmail.com)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
