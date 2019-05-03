<?php

use common\models\cms\WsPage;
use frontend\widgets\alias\AliasWidget;
use frontend\widgets\cms\SlideWidget;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $type string */

$this->beginContent('@frontend/views/layouts/common.php')
?>

<div class="keep-navbar">
    <div class="container">
        <?php echo AliasWidget::widget(['type' => $type,'isShow' => $type === WsPage::TYPE_HOME]); ?>
        <div class="search-box">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nhập từ khóa cần tìm"/>
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fas fa-search"></i></button>
                </span>
                </div>
            </div>
            <span class="search-tag"><i class="search-icon"></i></span>
        </div>
    </div>
</div>
<?php echo SlideWidget::widget(); ?>
<?php echo $content; ?>
<?php $this->endContent() ?>