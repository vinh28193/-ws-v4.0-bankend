<?php

/* @var yii\web\View $this */
/* @var common\components\StoreManager $storeManager */
/* @var string $content */
$this->beginContent('@frontend/views/layouts/common.php');
?>

<div class="container">
    <div class="auth-content style-margin-top">
        <div class="logo">
            <img src="/images/logo/weshop-02.png" alt="" title=""/>
        </div>
        <div class="auth-box">
            <div class="left">
                <?=$content;?>
            </div>
            <div class="right">
                <h2><?= Yii::t('frontend' , 'Member Benefits') ?></h2>
                <ul>
                    <li><?= Yii::t('frontend' , 'Shopping simple, fast') ?></li>
                    <li><?= Yii::t('frontend' , 'Track orders easily') ?></li>
                    <li><?= Yii::t('frontend' , 'Preferential price for members') ?></li>
                    <li><?= Yii::t('frontend' , 'Get attractive deals around the world') ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>