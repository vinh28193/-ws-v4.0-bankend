<?php

/* @var $this yii\web\View */
/* @var $block array */
/* @var $iphoneOld boolean */
/* @var $categories array */
/* @var $images array */
/* @var $grid array */

$imge_block = '/images/amazon/oke.png';
$imge_block = !empty($block['image']) ? $block['image'] : $imge_block;
?>

<div class="container">
    <div class="buy-amazon">
        <div class="left">
            <div class="title">
                <a href="http://amazon.com/"><img src="/img/logo_amz_white.png" alt="Amazon" title="Amazon"></a>
            </div>
            <div class="list">
                <ul>
                    <?php if (!empty($categories)) {
                        foreach ($categories as $key => $val) {
                            ?>
                            <li><a href="<?= $val['url'] ?>"><?= $val['name'] ?></a></li>
                            <?php
                        }
                    } ?>
                </ul>
            </div>
            <div class="banner-deal">
                <a href="/amazon.html">
                    <img src="<?= $imge_block ?>" alt="Deal" title="Deal">
                </a>
            </div>
        </div>
        <div class="right">
            <div class="top">
                <div class="title-mb hidden-md hidden-lg">
                    <a href="/amazon.html">
                        <img src="/images/amazon/amazon-logo.png" alt="Amazone" title="Amazone">
                    </a>
                </div>
                <ul>
                    <?php if (!empty($images)) {
                        foreach ($images as $key => $val) {

                            ?>

                            <li for="<?= $key + 1 ?>">
                                <a href="<?= $val['link'] ?>">
                                    <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $val['domain']; ?><?= $val['origin_src']; ?>" alt=""
                                    title="<?= $val['name'] ?>">
                                </a>
                            </li>

                            <?php
                        }
                    } ?>
                </ul>
            </div>
            <div class="middle">
                <?php if (isset($grid)) {
                    foreach ($grid as $key => $val) {
                        if ($key < 2) { ?>
                            <div class="banner-<?= $key + 1 ?>">
                                <a href="<?= $val['link']; ?>">
                                    <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $val['domain']; ?><?= $val['origin_src']; ?>" alt="<?= $val['name']; ?>">
                                </a>
                            </div>
                        <?php }
                    }
                } ?>
            </div>
            <div class="bottom">
                <?php if (isset($grid)) {
                    foreach ($grid as $key => $val) {
                        if ($key > 1 && $key < 8) { ?>
                            <div class="banner-<?= $key + 1 ?>">
                                <a href="<?= $val['link'] ?>">
                                    <img class="<?= $iphoneOld ? '' : 'lazy' ?>" <?= $iphoneOld ? 'src' : 'data-original' ?>="<?= $val['domain']; ?><?= $val['origin_src']; ?>" alt="<?= $val['name'];  ?>"
                                    title="<?= $val['name'] ?>">
                                </a>
                            </div>
                        <?php }
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
