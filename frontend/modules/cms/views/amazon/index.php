<?php

use frontend\widgets\WsLazyCMSWidget;

/* @var $this yii\web\View */
/* @var $page common\models\cms\WsPage */
/* @var $numPage integer */
/* @var $content array */

echo WsLazyCMSWidget::widget([
    'totalPage' => $numPage,
    'content' => $content
]);