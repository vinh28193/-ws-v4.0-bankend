<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 11:54
 */

use yii\helpers\Html;

/* @var $this \yii\web\View  */
/* @var $methods \weshop\payment\BasePaymentMethod[] */
/* @var $activeMethod \weshop\payment\BasePaymentMethod */
/* @var $activeName string */
?>

<div class="method-view">

    <div class="container main-container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <?php
                    foreach ($methods as $name => $method) {
                        $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . Html::encode($method->getName());
                        echo Html::a($label, $method->getAccessUrl(), [
                            'class' => $name === $activeName ? 'list-group-item active' : 'list-group-item',
                        ]);
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-9">
                    <?= $activeMethod->getDetail() ?>
            </div>
        </div>
    </div>

</div>
