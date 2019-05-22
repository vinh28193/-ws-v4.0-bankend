<?php
/**
 * @var $banks \common\models\PaymentBank[]
 */
?>

<div class="form-group">
    <div class="label">Tên ngân hàng</div>
    <select name="bank_id" class="form-control">
        <option value="">Chọn ngân hàng</option>
        <?php foreach ($banks as $bank) {?>
            <option value="<?= $bank->id ?>"><?= $bank->name ?></option>
        <?php }?>
    </select>
</div>
<div class="form-group">
    <div class="label">Số tài khoản</div>
    <input type="text" class="form-control" placeholder="Nhập số tài khoản" name="bank_account_number">
</div>
<div class="form-group">
    <div class="label">Chủ tài khoản</div>
    <input type="text" class="form-control" placeholder="Nhập tên chủ tài khoản" name="bank_account_name">
</div>
