<?php

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */
/* @var boolean $selected */
?>

<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?= $group; ?>"
       aria-expanded="<?= $selected ? 'true' : 'false'; ?>"
       onclick="ws.payment.selectMethod(<?= $methods[0]['payment_provider_id'] ?>,<?= $methods[0]['payment_method_id'] ?>, '<?= $methods[0]['paymentMethod']['code']; ?>')">
        <i class="icon method_<?= $group; ?>"></i>
        <div class="name">Alepay</div>
        <div class="desc">Trả góp qua cổng thanh toán Alepay</div>
    </a>

    <div id="method<?= $group; ?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne"
         data-parent="#payment-method">
        <div class="method-content office">
            <div class="installment-title">Bước 1: Chọn ngân hàng trả góp</div>
            <ul class="method-list" id="installmentBanks"></ul>
            <div class="installment-title">Bước 2: Chọn số tháng trả góp</div>
            <div class="installment-table" id="installmentPeriods"></div>
            <div class="form-group form-check term">
                <input type="checkbox" class="form-check-input" value="1" id="termInstallment">
                <label class="form-check-label" for="termInstallment">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và điều
                        kiện</a> giao dịch trả góp của Weshop.</label>
            </div>
        </div>
    </div>
</div>