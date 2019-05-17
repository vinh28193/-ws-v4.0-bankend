<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var integer $group */
/* @var common\payment\Payment $payment */
/* @var array $methods */

$imageUrl = function ($url){
    return '/'.str_replace('images/','img/',$url);
}
?>
<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method<?=$group;?>" aria-expanded="false">
        <i class="icon method_<?=$group;?>"></i>
        <div class="name">Thanh toán Quốc tế ( Credit, Debit)</div>
        <div class="desc">Số thẻ của bạn được giữ an toàn 100% và chỉ được sử dụng cho giao dịch này.</div>
    </a>

    <div id="method<?=$group;?>" class="collapse" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content">
            <ul class="method-list">
                <?php foreach ($methods as $idx => $method): ?>
                <li rel="s_bankCode" id="bank_code_<?=$method['paymentMethod']['code'];?>_<?=$method['payment_method_id']?>" onclick="ws.payment.selectMethod(<?=$method['payment_provider_id']?>,<?=$method['payment_method_id']?>, '<?=$method['paymentMethod']['code'];?>')">
                    <span class="<?= $idx === 0 ? "active" : "" ?>"><img src="<?=$imageUrl($method['paymentMethod']['icon']);?>" alt="<?= $method['paymentMethod']['name']; ?>" title="<?= $method['paymentMethod']['name'];?>"/></span>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>