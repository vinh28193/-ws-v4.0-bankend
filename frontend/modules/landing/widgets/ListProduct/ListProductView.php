<?php
/**
 * @var \common\models\cms\WsProduct $product
 */

use common\components\RedisLanguage;


$browser = \weshop\helpers\BrowserHepler::getBrowser();
$iphoneOld = false;
if(in_array($browser['versionOS'],[4,5,6,7,8,9])&& $browser['platform'] == 'iPhone|iPad'){
    $iphoneOld = true;
}
?>

<div class="container">
    <div class="lmkt-list-prod">
        <div class="lmkt-title">
            <h1><?= $block->name ?><a href="<?= $block->url ?>"><?= RedisLanguage::getLanguageByKey('header_massage_seeall','Xem tất cả')?></a>
            </h1>
        </div>
        <div class="lmkt-prod-top">
            <ul>
                <li class="lmkt-prod-full">
                    <ul class="lmkt-prod-other">
                        <?php if (isset($block->image)) { ?>
                            <li>
                                <div class="lmkt-prod-banner">
                                    <a href="<?= $block->url ?>">
                                        <img src="<?= $block->image ?>" alt="<?= $block->name ?>">
                                    </a>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if (isset($products)) {
                            foreach ($products as $key => $product) {
                                echo \weshop\modules\landing\views\widgets\ListProduct\ProductWidget::widget(['product' => $product]);
                            }
                        } ?>

                    </ul>
                </li>
            </ul>
        </div>
        <div class="lmkt-prod-bottom">
        </div>
    </div>
</div>