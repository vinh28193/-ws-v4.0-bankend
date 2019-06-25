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
use frontend\modules\payment\methods\CODWidget;
use frontend\modules\payment\methods\AlepayWidget;

/* @var yii\web\View $this */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $group */

/** @var  $storeManager  common\components\StoreManager */
$storeManager = Yii::$app->storeManager;
?>

<div class="accordion payment-method" id="payment-method">
    <?php

    foreach ($group as $id => $item) {
        switch ($id) {
            case PaymentService::PAYMENT_METHOD_GROUP_COD:
                echo CODWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_GROUP_INSTALMENT:
                echo AlepayWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_GROUP_MASTER_VISA:
                echo VisaMasterWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_NL_WALLET:
                echo NLWalletWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_GROUP_ATM:
                echo ATMOnlineWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_GROUP_QRCODE:
                echo QRCodeWidget::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_BANK_TRANSFER;
                echo BankTransferVN4::create($id, $item, $payment);
                break;
            case PaymentService::PAYMENT_METHOD_BANK_MCPAY;
                echo McpayWidget::create($id, $item, $payment);
                break;
            default:
                echo UnknownWidget::create($id, $item, $payment);
                break;
        }
    }
    ?>
</div>
<div class="form-group form-check term">
    <input type="checkbox" class="form-check-input" value="1" id="termCheckout">
    <label class="form-check-label" for="termCheckout">Tôi đồng ý với tất cả <a href="#" target="_blank">Điều khoản và
            điều
            kiện</a> giao dịch của Weshop.</label>
</div>
<div class="text-center">
    <button type="button" class="btn btn-payment" id="btnCheckout" onclick="ws.payment.process()">Thanh toán ngay
        <span> <?= $storeManager->showMoney($payment->getTotalAmountDisplay()); ?></span>
    </button>
</div>


