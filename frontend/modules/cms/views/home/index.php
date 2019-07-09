<?php

use yii\helpers\Html;
use frontend\widgets\WsStaticCMSWidget;

/* @var $this yii\web\View */
/* @var $page common\models\cms\WsPage */
/* @var $data array */

//Toto Page Title here

//$this->title = Yii::t('frontend','Weshop - Worldwide shopping made easy');
//echo WsStaticCMSWidget::widget([
//    'data' => $data
//]);
?>
<div class="content-body">
    <div class="title-content-home"><?= Yii::t('frontend','Worldwide shopping made easy') ?></div>
    <div class="row m-auto">
        <div class="col-md-3 col-sm-6">
            <div class="row">
                <div class="col-md-2  col-sm-2">
                    <i class="la la-check-circle"></i>
                </div>
                <div class="col-md-10  col-sm-10">
                    <div><b><?= Yii::t('frontend','Assurance shopping') ?></b></div>
                    <div class="mobile-hide"><?= Yii::t('frontend','Simple procedure, convenient quick payment.') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="row">
                <div class="col-md-2  col-sm-2">
                    <i class="la la-bar-chart"></i>
                </div>
                <div class="col-md-10  col-sm-10">
                    <div><b><?= Yii::t('frontend','Competitive service fee') ?></b></div>
                    <div class="mobile-hide"><?= Yii::t('frontend','Diversified services help you be flexible & optimize your costs.') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="row">
                <div class="col-md-2 col-sm-2">
                    <i class="la la-life-bouy"></i>
                </div>
                <div class="col-md-10 col-sm-10">
                    <div><b><?= Yii::t('frontend','Deliver to home') ?></b></div>
                    <div class="mobile-hide"><?= Yii::t('frontend','International shipping delivery to home.') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="row">
                <div class="col-md-2 col-sm-2">
                    <i class="la la-check-circle"></i>
                </div>
                <div class="col-md-10 col-sm-10">
                    <div><b><?= Yii::t('frontend','Product variety') ?></b></div>
                    <div class="mobile-hide"><?= Yii::t('frontend','Millions of products are available, continuous promotion updates.') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-auto tutorial-shop">
        <!--a href="<?= Yii::t('frontend','#guildShopping') ?>">
            <?= Yii::t('frontend','Instructions for ordering Weshop') ?><i class="la la-arrow-right"></i>
        </a-->
        <a href="https://blog.weshop.asia/help-center/huong-dan-mua-hang-tai-weshop/" target="_blank">
            <?= Yii::t('frontend','Instructions for ordering Weshop') ?><i class="la la-arrow-right"></i>
        </a>
    </div>
</div>
<?= WsStaticCMSWidget::widget(['data' => $data]); ?>
