<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
?>

<div id="installmentContent"></div>
<div class="form-group form-check term">
    <input type="checkbox" class="form-check-input" id="term">
    <label class="form-check-label" for="term">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và điều kiện</a> giao dịch của Weshop.</label>
</div>
<button type="submit" class="btn btn-payment btn-block">Thanh toán ngay</button>