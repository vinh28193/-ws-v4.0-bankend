<?php

?>

<div class="container">
    <div class="lmkt-list-prod">
        <div class="lmkt-title">
            <h2><?= $block['name'] ?><a href="<?= $block['url'] ?>">Xem tất cả</a>
            </h2>
        </div>
        <div class="lmkt-prod-top">
            <ul>
                <li class="lmkt-prod-full">
                    <ul class="lmkt-prod-other">
                        <?php if (isset($block['image'])) { ?>
                            <li>
                                <div class="lmkt-prod-banner">
                                    <a href="<?= $block['url'] ?>">
                                        <img src="<?= $block['image'] ?>" alt="<?= $block['name'] ?>">
                                    </a>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if (isset($products)) {
                            foreach ($products as $key => $product) {
                                echo \landing\widgets\ListProduct\ProductWidget::widget(['product' => $product]);
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