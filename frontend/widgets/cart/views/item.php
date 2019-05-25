<?php

use yii\helpers\Html;

/* @var string $key */
/* @var boolean $selected */
/* @var string $name */
/* @var string $type */
/* @var string $imageSrc */
/* @var string $originLink */
/* @var string $link */
/* @var string|common\products\Provider $provider */
/* @var string|null $variation */
/* @var string $condition */
/* @var int $quantity */
/* @var int $availableQuantity */
/* @var int $soldQuantity */
/* @var int $weight */
/* @var string|float $amount */
/* @var string|float $localAmount */
/* @var string $localDisplayAmount */

$name = Html::encode($name);
?>


<li data-key="<?= $key; ?>" data-type="<?= $type; ?>">
    <div class="form-check">
        <input class="form-check-input" <?= $selected ? 'checked' : '' ?> type="checkbox" name="cartItems"
               data-amount="<?= $amount; ?>" data-price="<?= $localAmount; ?>" value="<?= $key; ?>"
               id="cartItem<?= $key; ?>">
        <label class="form-check-label" for="cartItem<?= $key; ?>"></label>
    </div>
    <div class="thumb">
        <img src="<?= $imageSrc ?>" alt="<?= $name; ?>">
    </div>
    <div class="info">
        <div class="left">
            <a href="<?= $link ?>" target="_blank" class="name"><?= $name; ?></a>
            <div class="rate">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
            </div>
            <ol>
                <?php
                echo '<li>Bán bởi : <a href="' . (isset($provider['seller_link_store']) && $provider['seller_link_store'] !== null ? $provider['seller_link_store'] : "#") . '">' . $provider['seller_name'] . '</a></li>';
                if ($variation !== null && isset($variation['options_group'])) {
                    $var = [];
                    foreach ($variation['options_group'] as $options) {
                        $var[] = "{$options['name']}:{$options['value']}";
                    }
                    echo '<li> Thuộc tính:' . implode(' và ', $var) . '</li>';
                }
                ?>
                <li>Tình trạng :<?= $condition ?></li>
                <li>Tạm tính : <span class="weight-option"><?= $weight ?></span> kg</li>
            </ol>
        </div>
        <div class="right">
            <div class="qty form-inline quantity-option">
                <label>Số lượng:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary button-quantity-down" data-pjax="1"
                                data-for="<?= $key ?>"
                                data-update="#<?= $key ?>" data-operator="down" type="button">-
                        </button>
                    </div>
                    <input type="text" name="cartItemQuantity" class="form-control" value="<?= $quantity; ?>" data-min="1" id="<?= $key; ?>"
                           data-max="<?= $availableQuantity; ?>" aria-label="" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary button-quantity-up" data-pjax="1"
                                data-for="<?= $key ?>"
                                data-update="#<?= $key ?>" data-operator="up" type="button">+
                        </button>
                    </div>
                </div>
            </div>
            <div class="price price-option" data-original="<?= $amount; ?>"
                 data-local="<?= $localAmount; ?>"><?=$localDisplayAmount; ?>
            </div>
            <a href="#" class="del delete-item" data-key="<?= $key; ?>"><i class="far fa-trash-alt"></i> Xóa</a>
        </div>
    </div>
</li>
