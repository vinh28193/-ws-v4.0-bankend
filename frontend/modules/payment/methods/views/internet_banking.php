<?php

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */

/* @var boolean $selected */

use yii\helpers\Html; ?>
<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?= $group; ?>"
       aria-expanded="<?= $selected ? 'true' : 'false'; ?>" onclick="ws.payment.selectMethod(<?=$methods[0]['payment_provider_id']?>,<?=$methods[0]['payment_method_id']?>, '<?=$methods[0]['paymentMethod']['code'];?>')">
        <i class="icon method_<?= $group; ?>"></i>
        <div class="name">Thanh toán qua Internet Banking</div>
        <div class="desc">Miễn phí. Số thẻ của bạn được giữ an toàn 100% và chỉ được sử dụng cho giao dịch này.</div>
    </a>

    <div id="method<?= $group; ?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne"
         data-parent="#payment-method">
        <div class="method-content">
            <ul class="method-list">
                <?php foreach ($methods as $idx => $method): ?>
                    <li rel="s_bankCode"
                        id="bank_code_<?= $method['paymentMethod']['code']; ?>_<?= $method['payment_method_id'] ?>"
                        onclick="ws.payment.selectMethod(<?= $method['payment_provider_id'] ?>,<?= $method['payment_method_id'] ?>, '<?= $method['paymentMethod']['code']; ?>',true)">
                        <span class="<?= $idx === 0 ? "active" : "" ?>"><img
                                    src="<?= $method['paymentMethod']['icon']; ?>"
                                    alt="<?= $method['paymentMethod']['name']; ?>"
                                    title="<?= $method['paymentMethod']['name']; ?>"/></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-info">
                <div class="row">
                    <div class="col-md-12">
                        <div style="margin-bottom: 16px;font-weight: 700">Thông tin thẻ \ Tài khoản</div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Mã số thẻ: </label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0.65rem">
                                    <?php echo Html::input('text', 'freeze_balance', 13, ['class' => 'form-control']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tên chủ thẻ: </label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0.65rem">
                                    <?php echo Html::input('text', 'freeze_balance', 13, ['class' => 'form-control']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Phát Hành: </label>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 0.55rem;">
                                            <?php echo Html::input('text', 'freeze_balance', 13, ['class' => 'form-control']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-bottom: 0.65rem">
                                            <?php echo Html::input('text', 'freeze_balance', 13, ['class' => 'form-control']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>