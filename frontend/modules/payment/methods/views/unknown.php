<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */
/* @var boolean $selected */
?>


<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method_unknown<?=$group?>" aria-expanded="false">
        <i class="icon method_unknown<?=$group?>"></i>
        <div class="name">Unknown payment method group <?=$group;?></div>
        <div class="desc">please re config</div>
    </a>

    <div id="method_unknown<?=$group?>" class="<?= $selected ? 'collapse show' : 'collapse' ?>" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content">
            <p><b>NOT FOUND</b></p>
        </div>
    </div>
</div>

