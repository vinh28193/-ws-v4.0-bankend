<?php

use yii\helpers\Html;
use yii\web\JsExpression;

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */
/* @var boolean $selected */
/* @var array|null $wallet */

/** @var  $storeManager  common\components\StoreManager */
$storeManager = Yii::$app->get('storeManager');
$amountChange = $payment->total_amount > $wallet['usable_balance'] ? $payment->total_amount - $wallet['usable_balance'] : 0;
?>

<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?= $group; ?>"
       aria-expanded="<?= $selected ? 'true' : 'false'; ?>"
       onclick="ws.payment.selectMethod(<?= $methods[0]['payment_provider_id'] ?>,<?= $methods[0]['payment_method_id'] ?>, '<?= $methods[0]['paymentMethod']['code']; ?>')">
        <i class="icon method_<?= $group; ?>"></i>
        <div class="name">Weshop - Ewallet</div>
        <div class="desc">Qúy khách vui lòng chọn xác nhận mật khẩu</div>
    </a>

    <div id="method<?= $group; ?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne"
         data-parent="#payment-method">
        <div class="method-content wallet">
            <?= Html::a('<img src="/img/payment_wallet.png"/><span>Nạp tiền</span>', new JsExpression('/my-weshop/wallet/top-up.html?amount='.$amountChange), ['href' => '/my-weshop/wallet/top-up.html?amount='.$amountChange,'target' => '_blank','class' => 'btn btn-add-credit']) ?>
            <div class="row">
                <div class="col-md-6">
                    <label>Tổng số tiền chính trong tài khoản:</label>
                </div>
                <?php if ($wallet !== null): ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Html::input('text', 'current_balance', $storeManager->showMoney($wallet['current_balance']), ['class' => 'form-control', 'disabled' => true]); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Số tiền đóng băng: </label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Html::input('text', 'freeze_balance', $storeManager->showMoney($wallet['freeze_balance']), ['class' => 'form-control', 'disabled' => true]); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Số tiền có thể mua sắm:</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Html::input('text', 'usable_balance', $storeManager->showMoney($wallet['usable_balance']), ['class' => 'form-control', 'disabled' => true]); ?>
                        </div>
                    </div>
                    <?php if ($amountChange > 0): ?>
                        <div class="col-md-12">
                            <small class="warning text-orange">Tài khoản của bạn không đủ tiền. Vui lòng nạp tiền để
                                tiến
                                hành sử dụng
                            </small>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <b>Chọn hình thức nhận OTP:</b>
                    </div>

                    <?php
                        echo Html::radioList('otpVerifyMethod', $payment->otp_verify_method, [
                            0 => 'Phone number' . ' (' . $wallet['customer_phone'] . ')',
                            1 => 'Email' . ' (' . $wallet['email'] . ')'
                        ], [
                            'tag' => 'div',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<div class="form-check">' . Html::radio($name, $checked, ['value' => $value, 'id' => "wallet{$value}", 'class' => 'form-check-input']) . Html::label($label, "wallet{$value}", ['class' => 'form-check-label']) . '</div>';
                            },
                            'class' => 'col-md-6'
                        ]);
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
