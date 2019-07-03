<?php

use frontend\widgets\layout\FooterWidget;
use frontend\widgets\layout\HeaderWidget;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View *//* @var $content string */;
?>

<?php $this->beginContent("@landing/layouts/common.php"); ?>
<div class="wrapper">
    <?= HeaderWidget::widget(); ?>

    <?= $content; ?>

    <?= FooterWidget::widget(); ?>

    <span class=" gotop is-animating" style="margin-right: 30px;"></span>
</div>
<?php $this->endContent(); ?>
