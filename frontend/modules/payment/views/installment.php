<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
?>

<div class="installment-title">Bước 1: Chọn hình thức trả góp</div>
<ul class="method-list" id="installmentBanks"></ul>
<div class="installment-title">Bước 2: Chọn loại thẻ thanh toán</div>
<ul class="method-list" id="installmentMethods"></ul>
<div class="installment-title">Bước 3: Chọn số tháng trả góp</div>
<div class="installment-table" id="installmentPeriods"></div>
<div class="form-group form-check term">
    <input type="checkbox" class="form-check-input" value="1" id="termInstallment">
    <label class="form-check-label" for="termCheckout">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và điều
            kiện</a> giao dịch trả góp của Weshop.</label>
</div>
<button type="button" class="btn btn-payment btn-block" id="btnInstallment" onclick="ws.payment.installment()">Thanh toán trả góp ngay
</button>