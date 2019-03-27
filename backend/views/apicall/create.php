<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modelsMongo\RestApiCall */

$this->title = 'Create Rest Api Call';
$this->params['breadcrumbs'][] = ['label' => 'RestApiCall', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="RestApiCall-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
