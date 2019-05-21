<?php
?>
<div class="form-group">
    <i style="font-size: 10px; font-weight: 700">* Nếu chưa có tài khoản Ngân lượng. Bạn có thể đăng ký tại <a target="_blank" href="https://account.nganluong.vn/nganluong/Register.html">đây</a></i>
</div>
<div class="form-group">
    <div class="label">Email tài khoản Weshop</div>
    <b><?= Yii::$app->user->getIdentity()->email ?></b>
</div>
<div class="form-group">
    <div class="label">Email tài khoản Ngân lượng</div>
    <input type="email" class="form-control" name="email" placeholder="Email tài khoản Ngân Lượng">
</div>
