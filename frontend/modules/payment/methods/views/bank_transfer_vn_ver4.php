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
            <p class="mb-1">Số tài khoản: <b>0451000277143</b></p>
            <p class="mb-1">Chủ tài khoản: <b>Nguyễn Thị Hằng</b></p>
            <p class="mb-1">Ngân hàng: <b>Vietcombank (Ngân hàng thương mại cổ phần Ngoại thương Việt Nam) </b> chi nhánh Thành Công</p>
            <p class="">Nội dung chuyển khoản cần ghi rõ: <span class="banking-content text-blue"><b>Weshop</b> Số điện thoại của bạn</span></p>
            <p class="">Ví dụ: Weshop 0988380918</p>
            <p class="note"><span class="text-orange">(*)</span> Với những giao dịch nạp tiền chuyển khoản ngoài giờ hành chính. Thời gian nạp tiền vào hệ thống sẽ chậmhơn bình thường. Mong quý khách thông cảm!</p>
            <p class="mb-1">Hoặc quét mà QR Code:</p>
            <img style="width: 250px" src="/images/0451000277143.jpg" alt="0451000277143 Nguyễn Thị Hằng Vietcombank">
            <p class="mb-1"></p>
        </div>
    </div>
</div>
