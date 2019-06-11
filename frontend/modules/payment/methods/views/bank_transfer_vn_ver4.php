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
        <div class="desc">Qúy khách vui lòng chuyển tiền tới tài khoản sau</div>
    </a>

    <div id="method<?=$group;?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content banking">
            <p>Số tài khoản: <b>19003868470019</b> - Chủ tài khoản: <b>Vi Thị Hạnh</b> - Ngân hàng: <b>Techcombank</b> chi nhánh Lĩnh Nam</p>
            <p>Nội dung chuyển khoản cần ghi rõ:</p>
            <div class="banking-content text-blue"><b>Naptien</b> Số điện thoại đăng ký tài khoản Weshop</div>
            <p>Ví dụ: Naptien 0988380918</p>
            <p class="note"><span class="text-orange">(*)</span> Với những giao dịch nạp tiền chuyển khoản ngoài giờ hành chính. Thời gian nạp tiền vào hệ thống sẽ chậmhơn bình thường. Mong quý khách thông cảm!</p>
        </div>
    </div>
</div>
