<?php

use frontend\modules\payment\Payment;
use frontend\modules\payment\methods\VisaMasterWidget;
use frontend\modules\payment\methods\BankTransferWidget;
use frontend\modules\payment\methods\NLWalletWidget;
use frontend\modules\payment\methods\WSOfficeWidget;
use frontend\modules\payment\methods\McpayWidget;
use frontend\modules\payment\methods\UnknownWidget;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\methods\ATMOnlineWidget;
use frontend\modules\payment\methods\InternetBankingWidget;
use frontend\modules\payment\methods\QRCodeWidget;
use frontend\modules\payment\methods\BankTransferVN4;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $group */

?>

<div class="accordion payment-method" id="payment-method">
    <?php
    foreach ($group as $id => $item) {

        switch ($id) {
            case PaymentService::PAYMENT_GROUP_MASTER_VISA:
                echo VisaMasterWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_BANK:
                echo BankTransferWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_NL_WALLET:
                echo NLWalletWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_ATM:
                echo ATMOnlineWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_IB:
                echo InternetBankingWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_MCPAY:
                echo McpayWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_QRCODE:
                echo QRCodeWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_WSVP:
                echo WSOfficeWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_COD:
                echo UnknownWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_GROUP_VN_BANK_TRANSFER:
                echo BankTransferVN4::create($id, $item, $payment);
                break;
            default:
                echo UnknownWidget::create($id, $item, $payment);
                break;

        }
    }
    ?>
</div>
<?php if ($payment->page === Payment::PAGE_TOP_UP) { ?>
    <div class="form-group form-inline">
        <label>Nhập số tiền cần nạp &nbsp;<i class="la la-question-circle" title="Tối thiểu phải là 100.000 VNĐ"></i>:</label>
        <input type="number" class="form-control" name="amount_topup" placeholder="Ví dụ: 100.000 VNĐ"
               value="<?= Yii::$app->request->get('amount') ?>">
    </div>
<?php } ?>
<div class="form-group form-check term">
    <input type="checkbox" class="form-check-input" value="1" id="termCheckout">
    <label class="form-check-label" for="termCheckout">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và
            điều
            kiện</a> giao dịch của Weshop.</label>
</div>
<button type="button" class="btn btn-payment btn-block" id="btnCheckout" onclick="ws.payment.process()">Thanh toán ngay
</button>

