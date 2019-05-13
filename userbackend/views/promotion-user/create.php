<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\PromotionUser */

$this->title = 'Create Promotion User';
$this->params['breadcrumbs'][] = ['label' => 'Promotion Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotion-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
