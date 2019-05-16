<?php

use common\payment\Payment;
use common\payment\methods\VisaMasterWidget;
use common\payment\methods\BankTransferWidget;
use common\payment\methods\NLWalletWidget;
use common\payment\methods\WSOfficeWidget;
use common\payment\methods\WSWalletWidget;
use common\payment\methods\UnknownWidget;

/* @var yii\web\View $this */
/* @var common\payment\Payment $payment */
/* @var array $group */
?>

<div class="accordion payment-method" id="payment-method">
    <?php
    foreach ($group as $id => $item) {
        switch ($id) {
            case Payment::PAYMENT_GROUP_MASTER_VISA:
                echo VisaMasterWidget::create($id, $item, $payment);
                break;
            case Payment::PAYMENT_GROUP_BANK:
                echo BankTransferWidget::create($id, $item, $payment);
                break;
            case Payment::PAYMENT_GROUP_NL_WALLET:
                echo NLWalletWidget::create($id, $item, $payment);
                break;
            case Payment::PAYMENT_GROUP_WSVP:
                echo WSOfficeWidget::create($id, $item, $payment);
                break;
            case Payment::PAYMENT_GROUP_WS_WALLET:
                echo WSWalletWidget::create($id, $item, $payment);
                break;
            case Payment::PAYMENT_GROUP_COD:
                echo UnknownWidget::create($id, $item, $payment);
                break;
            default:
                echo UnknownWidget::create($id, $item, $payment);
                break;

        }
    }
    ?>
</div>
<div class="form-group form-check term">
    <input type="checkbox" class="form-check-input" id="termCheckout">
    <label class="form-check-label" for="term">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và điều
            kiện</a> giao dịch của Weshop.</label>
</div>
<button type="submit" class="btn btn-payment btn-block" id="btnCheckout" data-toggle="modal"
        data-target="#checkout-success" onclick="ws.payment.createOrder()">Thanh toán
    ngay
</button>
