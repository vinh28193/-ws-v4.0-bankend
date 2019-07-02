<?php

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */
/* @var boolean $selected */
?>

<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?=$group;?>" aria-expanded="<?=$selected ? 'true' : 'false';?>" onclick="ws.payment.selectMethod(<?=$methods[0]['payment_provider_id']?>,<?=$methods[0]['payment_method_id']?>, '<?=$methods[0]['paymentMethod']['code'];?>')">
        <i class="icon method_<?=$group;?>"></i>
        <div class="name">Chuyển khoản ngân hàng</div>
        <div class="desc"><span class="text-danger">Miễn phí giao dịch</span></div>
    </a>

    <div id="method<?=$group;?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content banking">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <p class="note"><span class="text-orange">(*)</span> Với những giao dịch nạp tiền chuyển khoản ngoài giờ hành chính. Thời gian nạp tiền vào hệ thống sẽ chậm hơn bình thường. Mong quý khách thông cảm!</p>
                </div>
            </div>
        </div>
    </div>
</div>
