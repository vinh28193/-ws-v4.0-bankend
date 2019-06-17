<?php

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $categories array */
/* @var $images array */
/* @var $grid array */

$imge_block = 'http://static.weshop.com.vn/upload/q/d/m/e/5/i/3/8/p/g/^DF5924AC6867E2761FB5AEDA50DBB162CEFDA5AC63D5D1AD80^pimgpsh_fullsize_distr.png';
$imge_block = !empty($block['image']) ? $block['image'] : $imge_block;
?>

<div class="container">
    <div class="buy-amazon buy-ebay ebay-block">
        <div class="left">
            <span class="ebay-line"><img src="/img/ebay-line.png"/></span>
            <div class="title">
                <a href="<?= $block['url'] ?  $block['url'] : "/ebay.html"?>">
                    <img src="<?= $block['portal_logo'] ?  $block['portal_logo'] : "/img/logo_ebay_white.png"; ?>" alt="" title="">
                </a>
                <a href="http://ebay.com/"><img src=""/></a>
            </div>
            <div class="list">
                <ul>
                    <?php if (!empty($categories)) {
                        foreach ($categories as $key => $val) { ?>
                            <li><a href="<?= $val['url'] ?>"><?= $val['name'] ?></a></li>
                        <?php }
                    } ?>
                </ul>
            </div>
            <div class="banner-deal">
                <a href="<?= $block['url'] ?  $block['url'] : "/ebay.html"?>">
                    <img src="<?= $imge_block ?>" alt="Weshop Portal" title="Weshop Portal">
                </a>
            </div>
        </div>
        <div class="right">
            <div class="top top-mb">
                <div class="title-mb mobile-show hidden-md hidden-lg ipad-show">
                    <a href="/ebay.html">
                        <img src="https://static-v3.weshop.com.vn/uploadImages/8ae0ba/8ae0ba2d425a42295b0a85fe081b7534.png" alt="" title="">
                    </a>
                </div>
                <ul>
                    <?php if (!empty($images)) {
                        foreach ($images as $key => $val) {
                            ?>

                            <li for="<?= $key + 1 ?>">
                                <a class="ebay-branch-logo-<?= $key + 1 ?>" href="<?= $val['link']?>" title="<?= $val['name'] ?>"></a>
                            </li>

                            <?php
                        }
                    } ?>
                </ul>
            </div>
            <div class="bottom">
                <?php if (isset($grid)) {
                    foreach ($grid as $key => $val) {
                        if ($key < 5) { ?>
                            <div class="banner-<?= $key + 1 ?>">
                                <a href="<?= $val['link'];?>">
                                    <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $val['domain'] ?><?= $val['origin_src'] ?>" alt="<?= $val['name']; ?>"
                                    title="<?= $val['name']; ?>">
                                </a>
                            </div>
                        <?php }
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
<span class=" gotop is-animating" style="margin-right: 30px;"></span>