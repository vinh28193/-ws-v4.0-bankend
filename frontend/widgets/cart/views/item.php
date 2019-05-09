<?php

use yii\helpers\Html;

/* @var string $key*/
/* @var string $name */
/* @var string $type */
/* @var string $imageSrc */
/* @var string $originLink */
/* @var string $link */
/* @var string|common\products\Provider $provider */
/* @var string|null $variation */
/* @var int $quantity */
/* @var int $availableQuantity */
/* @var int $soldQuantity */
/* @var int $weight */
/* @var int|string|float $price */

$name = Html::encode($name);
?>


<li data-key="<?=$key;?>" class="item">
    <div class="thumb">
        <img src="<?= $imageSrc ?>" alt="<?= $name; ?>">
    </div>
    <div class="info">
        <div class="left">
            <a href="#" class="name"><?= $name; ?></a>
            <div class="rate">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
            </div>
            <ol>
                <?php
                if (is_string($provider)) {
                    echo '<li>Bán bởi:' . $provider . '</li>';
                } else if ($provider instanceof \common\products\Provider) {
                    echo '<li>Bán bởi: <a href="' . ($provider->website !== null ? $provider->website : "#") . '">' . $provider->name . '</a></li>';
                }
                if ($variation !== null) {
                    echo '<li>' . $variation . '</li>';
                }
                ?>

                <li>Tạm tính: <?= $weight ?> Gram</li>
            </ol>
        </div>
        <div class="right">
            <div class="qty form-inline quantity-option">
                <label>Số lượng:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary button-quantity-up" data-for="<?=$key?>" data-update="#<?=$key?>" type="button">-</button>
                    </div>
                    <input type="text" class="form-control" value="<?= $quantity; ?>" data-min="1" id="<?=$key;?>"
                           data-max="<?= $availableQuantity; ?>" aria-label="" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary button-quantity-down" data-for="<?=$key?>" data-update="#<?=$key?>" type="button">+</button>
                    </div>
                </div>
            </div>
            <div class="price"><?= $price; ?> <i class="currency">đ</i></div>
            <a href="#" class="del"><i class="far fa-trash-alt"></i> Xóa</a>
        </div>
    </div>
</li>