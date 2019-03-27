<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modelsMongo\PaymentLogWS */

$this->title = 'Create Rest Api Call';
$this->params['breadcrumbs'][] = ['label' => 'PaymentLogWS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="PaymentLogWS-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
