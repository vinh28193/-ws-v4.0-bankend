<?php


?>

<div class="modal-title">Xác thực OTP</div>
<p><?= $msg ?></p>
<form>
    <div class="form-group">
        <label>Mã OTP</label>
        <input type="text" class="form-control text-center">
    </div>
    <div class="form-group">
        <label>Mã xác nhận</label>
        <div class="input-group">
            <input type="text" class="form-control">
            <div class="input-group-append">
                <span class="input-group-text"><img
                            src="https://thefallenbrain.files.wordpress.com/2016/05/input-black.gif"/></span>
            </div>
        </div>
    </div>
    <p>Bạn chưa nhận được mã OTP? <a href="#">Gửi lại</a></p>
    <button type="button" class="btn btn-submit btn-block">Xác thực</button>
</form>
