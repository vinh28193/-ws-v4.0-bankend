<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var integer $group */
/* @var frontend\modules\payment\Payment $payment */
/* @var array $methods */
?>


<div class="method-item">
    <a class="btn method-select" data-toggle="collapse" data-target="#method_unknown" aria-expanded="false">
        <i class="icon method_unknown"></i>
        <div class="name">unknown payment method group <?=$group;?></div>
        <div class="desc">please re config</div>
    </a>

    <div id="method_unknown" class="collapse" aria-labelledby="headingOne" data-parent="#payment-method">
        <div class="method-content">
            <p><b>NOT FOUND</b></p>
            <pre>
                <?php var_dump($methods);?>
            </pre>
        </div>
    </div>
</div>

