<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\PromotionUser */

$this->title = 'Update Promotion User: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promotion Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="promotion-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
