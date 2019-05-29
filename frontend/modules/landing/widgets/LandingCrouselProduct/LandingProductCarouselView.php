<?php

use common\components\RedisLanguage;

?>
<div class="container">
    <div class="lmkt-prod-slide">
        <div class="lmkt-title">
            <h1><?= $block->name ?><a
                        href="<?= $block->url ?>"><?= RedisLanguage::getLanguageByKey('header_massage_seeall', 'Xem tất cả') ?></a>
            </h1>
        </div>
        <div class="lmkt-prod-all">
            <ul>
                <?php if (!empty($products)) {
                    foreach ($products as $key => $product) { ?>
                        <li>
                            <div class="lmkt-prod-detail">
                                <div class="lmkt-prod-img">
                                    <a href="<?= $product['item_url']; ?>"
                                       class="lmkt-link-img">

                                        <img src="<?= $product['image']; ?>"
                                             alt="LEGO Star Wars AT-ST Walker 75153 Star Wars Toy">
                                    </a>

                                    <?php if (!empty($product['start_price'])) { ?>
                                        <span class="lmkt-sale">-<?= round($product['rate_count']) ?> %</span>
                                    <?php } ?>

                                    <div class="lmkt-cart">
                                        <a href="<?= $product['item_url']; ?>">detail</a>
                                    </div>
                                </div>
                                <div class="lmkt-prod-tit">
                                    <a href="<?= $product['item_url']; ?>"><?= $product['name']; ?></a>
                                </div>
                                <div class="lmkt-prod-pri">
                                    <p><?= $product['calculated_sell_price']; ?>
                                        <span><?= $product['calculated_start_price'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <?php
                    }
                } ?>

            </ul>
        </div>
    </div><!--best-choice-->
</div>
