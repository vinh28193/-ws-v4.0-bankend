<?php

use yii\helpers\Html;
use frontend\widgets\cms\ProductWidget;

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $categories array */
/* @var $products array */
/* @var $images array */
/* @var $keywords string */

?>

<div class="product-block">
    <div class="container">
        <div class="title-box">
            <div class="title"><?= $block['name']; ?></div>
            <ul class="title-breadcrumb">
                <?php foreach ($categories as $category): ?>
                    <li><a href="<?= $category['url']; ?>"><?= $category['local_name'] ?></a></li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= $block['url'] ?>" class="see-all">Xem tất cả</a>
        </div>
        <div class="pd-content">
            <div class="left">
                <a href="<?= $block['url'] ?>">
                    <?= Html::img($block['image'] !== null ? $block['image'] : 'http://static.weshop.com.vn/upload/2/k/u/n/8/8/d/u/c/v/2.png', []); ?>
                </a>
            </div>
            <div class="right">
                <div class="right-1">
                    <ul>
                        <?php
                        foreach ($products as $key => $product) {
                            if ($key < 4) {
                                echo ProductWidget::widget([
                                    'product' => $product,
                                    'type' => ProductWidget::TYPE_RIGHT
                                ]);
                            }
                        } ?>
                    </ul>
                </div>
                <div class="right-2">
                    <ul>
                        <?php
                        foreach ($products as $key => $product) {
                            if ($key > 3 && $key < 7) {
                                echo ProductWidget::widget([
                                    'product' => $product,
                                    'type' => ProductWidget::TYPE_LEFT
                                ]);
                            }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="pd-tag-box">
            <div class="tag-box box-1">
                <div class="title">Từ khóa tìm kiếm nhiều</div>
                <div class="keyword">
                    <?php
                        foreach (explode(',',$keywords) as $keyword){
                            echo Html::a($keyword,'#');
                        }
                    ?>
                    <a href="#">đồng hồ đeo tay</a>, <a href="#">rolex</a>, <a href="#">seiko</a>, <a
                            href="#">casio</a>, <a href="#">đồng hồ cơ</a>, <a href="#">đồng hồ thông minh</a></div>
            </div>
            <div class="brand-list">
                <ul>
                    <?php foreach ($images as $image): ?>
                        <li>
                            <a href="<?= $image['link'] ?>">
                                <img src="<?= $image['domain'] ?><?= $image['origin_src'] ?>"
                                        alt="<?= $image['name'] ?>"
                                        title="<?= $image['name'] ?>">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>