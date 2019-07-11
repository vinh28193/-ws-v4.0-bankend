<?php
?>
<div class="container">
    <div class="lmkt-list-prod">
        <div class="lmkt-title">
            <h2><?php echo $block['name'] ?><a href="<?php echo $block['url'] ?>">Xem tất cả'</a>
            </h2>
        </div>
        <div class="lmkt-prod-top">
            <ul>
                <li>
                    <?php if (!empty($products)) {
                        ?>
                        <ul class="lmkt-prod-other">
                            <li>
                                <div class="lmkt-prod-detail">
                                    <div class="lmkt-prod-img">
                                        <a href="<?= $products[0]['item_url'] ?>"
                                           class="lmkt-link-img">
                                            <img src="<?php echo $products[0]['image']; ?>">
                                        </a>

                                        <?php if (!empty($products[0]['start_price']) && !empty($products[0]['rate_count']) ) { ?>
                                            <span class="lmkt-sale">-<?= $products[0]['rate_count'] ?>%</span>
                                        <?php } ?>
                                        <div class="lmkt-cart">
                                            <a href="<?= $products[0]['item_url'] ?>">detail</a>
                                        </div>
                                    </div>
                                    <div class="lmkt-prod-tit">
                                        <a href="<?= $products[0]['item_url'] ?>"><?= $products[0]['name'] ?></a>
                                    </div>
                                    <div class="lmkt-prod-pri">
                                        <p>
                                            <?= $products[0]['calculated_sell_price'] ?>
                                            <span><?= $products[0]['calculated_start_price'] ?></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="lmkt-prod-other">
                            <li>
                                <div class="lmkt-prod-detail">
                                    <div class="lmkt-prod-img">
                                        <a href="<?= $products[1]['item_url'] ?>"
                                           class="lmkt-link-img">
                                            <img src="<?php echo $products[1]['image']; ?>" alt="Pandemic Board Game">
                                        </a>

                                        <?php if (!empty($products[1]['start_price']) && ($products[1]['rate_count'] != '') ) { ?>
                                            <span class="lmkt-sale">-<?= $products[1]['rate_count'] ?>%</span>
                                        <?php } ?>

                                        <div class="lmkt-cart">
                                            <a href="<?= $products[1]['item_url'] ?>">detail</a>
                                        </div>
                                    </div>
                                    <div class="lmkt-prod-tit">
                                        <a href="<?= $products[1]['item_url'] ?>"><?= $products[1]['name'] ?></a>
                                    </div>
                                    <div class="lmkt-prod-pri">
                                        <p>
                                            <?= $products[1]['calculated_sell_price'] ?>
                                            <span><?= $products[1]['calculated_start_price'] ?></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    <?php } ?>

                </li>
                <li>
                    <div class="lmkt-prod-banner1">
                        <a href="#"><img src="<?= $block['image'] ?>" alt=""></a>
                    </div>
                </li>
                <li>
                    <ul class="lmkt-prod-other">
                        <li>
                            <div class="lmkt-prod-detail">
                                <div class="lmkt-prod-img">
                                    <a href="<?= $products[3]['item_url'] ?>"
                                       class="lmkt-link-img">
                                        <img src="<?php echo $products[3]['image']; ?>" alt="Codenames">
                                    </a>

                                    <?php if (!empty($products[3]['start_price']) && !empty($products[0]['rate_count']) ) { ?>
                                        <span class="lmkt-sale">-<?= $products[3]['rate_count'] ?>%</span>
                                    <?php } ?>

                                    <div class="lmkt-cart">
                                        <a href="<?= $products[3]['item_url'] ?>">detail</a>
                                    </div>
                                </div>
                                <div class="lmkt-prod-tit">
                                    <a href="<?= $products[3]['item_url'] ?>"><?= $products[3]['name'] ?></a>
                                </div>
                                <div class="lmkt-prod-pri">
                                    <p>
                                        <?= $products[3]['calculated_sell_price'] ?>
                                        <span><?= $products[3]['calculated_start_price'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="lmkt-prod-other">
                        <li>
                            <div class="lmkt-prod-detail">
                                <div class="lmkt-prod-img">
                                    <a href="<?= $products[2]['item_url'] ?>"
                                       class="lmkt-link-img">
                                        <img src="<?php echo $products[2]['image']; ?>" alt="Catan 5th Edition">
                                    </a>


                                    <?php if (!empty($products[0]['rate_count'])  && $products[0]['rate_count'] !='' ) { ?>
                                        <span class="lmkt-sale">-<?= $products[2]['rate_count'] ?>%</span>
                                    <?php } ?>

                                    <div class="lmkt-cart">
                                        <a href="<?= $products[2]['item_url'] ?>">detail</a>
                                    </div>
                                </div>
                                <div class="lmkt-prod-tit">
                                    <a href="<?= $products[2]['item_url'] ?>"><?= $products[2]['name'] ?></a>
                                </div>
                                <div class="lmkt-prod-pri">

                                    <p>
                                        <?= $products[2]['calculated_sell_price'] ?>
                                        <span><?= $products[2]['calculated_start_price'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="lmkt-prod-full">
                    <ul class="lmkt-prod-other">
                        <?php if (!empty($products)) {
                            foreach ($products as $key => $product) {
                                if ($key > 3) { ?>
                                    <li>
                                        <div class="lmkt-prod-detail">
                                            <div class="lmkt-prod-img">
                                                <a href="<?= $product['item_url'] ?>"
                                                   class="lmkt-link-img">
                                                    <img
                                                        src="<?= $product['image'] ?>"
                                                        alt="<?= $product['name'] ?>">
                                                </a>
                                                <div class="lmkt-cart">
                                                    <a href="<?= $product['item_url'] ?>">detail</a>
                                                </div>
                                            </div>
                                            <div class="lmkt-prod-tit">
                                                <a href="<?= $product['item_url'] ?>">
                                                    <?= $product['name'] ?>
                                                </a>
                                            </div>
                                            <div class="lmkt-prod-pri">
                                                <p>
                                                    <?= $product['calculated_sell_price'] ?>
                                                    <span><?= $product['calculated_start_price'] ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
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