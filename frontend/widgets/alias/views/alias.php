<?php

use yii\helpers\Html;
use yii\helpers\Json;
use frontend\widgets\alias\AliasProductSliderWidget;
use frontend\widgets\alias\AliasCategoryListWidget;
use frontend\widgets\alias\AliasImageGridWidget;

/* @var $this yii\web\View */
/* @var $type string */
/* @var $isShow boolean */
/* @var $alias array */
/* @var $landing array */
/* @var $categories array */
/* @var $images array */

?>

<div class="cate-nav">
    <ul>
        <li class="globe-sub <?= $isShow ? 'open' : '' ?>">
            <a href="#"><i class="fas fa-globe-americas"></i> <span class="text-title"><?= $alias['name']; ?></span></a>
            <div class="sub-menu animated fadeIn">
                <ul>
                    <?php
                    $i = 1;
                    foreach ($landing as $item):
                        ?>
                        <li>
                            <a href="#">
                                <div class="name"><?= $item['name']; ?></div>
                                <div class="desc"><?= $item['desc']; ?></div>
                                <i class="fa fa-angle-right"></i>
                            </a>
                            <div class="sub-menu-2">
                                <div class="title-box">
                                    <div class="title"> <?= $item['name']; ?></div>
                                    <div class="sub-title"><?= $item['desc']; ?></div>
                                </div>
                                <?php echo AliasProductSliderWidget::widget([
                                    'wsProductGroups' => $item['wsProductGroups'][0],
                                    'index' => 'globe-sub-slider-' . $i,
                                    'wsAliasItem' => $item
                                ]); ?>
                            </div>
                        </li>
                        <?php
                        $i++;
                    endforeach;
                    foreach ($categories as $category):
                        ?>
                        <li class="<?= $alias['name_icon'] == null ? 'brand' : 'portal'; ?>">
                            <a href="<?= $category['url']; ?>"><?=$category['name'];?>
                                <img src="/img/logo_amz.png" alt="" class="brand-logo"/>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <div class="sub-menu-2">
                                <div class="ebay-sub-menu <?= $alias['name_icon'] ? $alias['name_icon'] : 'amazon' ?>">
                                    <?php
                                    echo AliasCategoryListWidget::widget([
                                        'wsCategoryGroups' => count($category['wsCategoryGroups']) > 0 ? $category['wsCategoryGroups'][0] : null,
                                        'wsAliasItem' => $category
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php
                    endforeach;
                    foreach ($images as $image):
                    ?>
                    <li class="<?= $alias['name_icon'] == null ? 'brand' : 'portal'; ?>">
                        <a href="<?= $category['url']; ?>"><?=$category['name'];?></a>
                        <div class="sub-menu-2 top-store-cate">
                            <?php
                            echo AliasImageGridWidget::widget([
                                'wsImageGroups' => $image['wsImageGroups'][0]
                            ]);
                            ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" name="menu-global-slide" value="<?=$i ?>"/>
            </div>
        </li>
        <?php if ($alias['alias_categories'] !== null): ?>
            <?php
            $aliasCategories= Json::decode($alias['alias_categories'], true);
            ?>
            <?php foreach ($aliasCategories as $aliasCategory): ?>
                <li>
                    <a href="<?= $aliasCategory['url'] ?>"><?= $aliasCategory['name'] ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
