<?php

use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $items array */
$storeManager = Yii::$app->storeManager;
?>

<div class="cart-box" style="border: none">
    <?php foreach ($items as $item):
            $products = ArrayHelper::getValue($item, 'products', []);

            ?>
            <ul class="cart-item" data-key="<?= $item['key'] ?>" data-type="shopping"
                style="border: 1px solid #e3e3e3;margin-bottom:10px">
                <li>
                    <?php $seller = ArrayHelper::getValue($item, 'seller', []); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="cartOrder" <?= $item['selected'] ? 'checked' : ''; ?>
                               value="<?= $item['key']; ?>" id="cartOrder<?= $item['key']; ?>">
                        <label class="form-check-label" for="cartOrder<?= $item['key']; ?>">
                            Bán <?= isset($seller['portal']) ? ('trên ' . Html::tag('span', strtolower($seller['portal']), ['style' => 'font-weight: 500;color: #393939'])) : ''; ?>
                            bởi:
                            <a style="color: #2b96b6;"
                               href="<?= isset($seller['seller_link_store']) ? $seller['seller_link_store'] : '#' ?>"><?= isset($seller['seller_name']) ? $seller['seller_name'] : 'Unknown'; ?></a>
                        </label>
                    </div>
                    <span style="margin-left: 0.5rem" class="orderSummary">(<?= count($products); ?> sản phẩm)</span>
                </li>
                <?php foreach ($products as $product): ?>
                    <?php
                    $availableQuantity = $product['available_quantity'];
                    $soldQuantity = $product['quantity_sold'];
                    $variations = ArrayHelper::getValue($product, 'variations');
                    if ($variations !== null) {
                        if (($variationSoldQuantity = ArrayHelper::getValue($variations, 'quantity_sold', $soldQuantity)) !== null && $variationSoldQuantity !== $soldQuantity) {
                            $soldQuantity = $variationSoldQuantity;
                        }
                        if (($variationAvailableQuantity = ArrayHelper::getValue($variations, 'available_quantity', $availableQuantity)) !== null && $variationAvailableQuantity !== $availableQuantity) {
                            $availableQuantity = $variationAvailableQuantity;
                        }
                    }
                    $availableQuantity = !($availableQuantity === null || (int)$availableQuantity < 0) ? $availableQuantity : 50;
                    $soldQuantity = !($soldQuantity === null || (int)$soldQuantity < 0) ? $soldQuantity : 0;
                    ?>
                    <li>
                        <div class="thumb" style="height: auto;">
                            <img src="<?= $product['link_img']; ?>"
                                 alt="<?= $product['product_name']; ?>" title="<?= $product['product_name']; ?>">
                        </div>
                        <div class="info">
                            <div class="left">
                                <a href="<?= $product['product_link']; ?>" target="_blank" class="name">
                                    <?= $product['product_name']; ?></a>
                                <div class="rate" style="text-align: left">
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star-half-o"></i>
                                    <i class="la la-star-o"></i>
                                </div>
                                <ol>
                                    <?php
                                    if ($variations !== null && isset($variations['options_group'])) {
                                        $var = [];
                                        foreach ($variations['options_group'] as $options) {
                                            $var[] = "{$options['name']} : {$options['value']}";
                                        }
                                        echo '<li> Thuộc tính: ' . implode(' và ', $var) . '</li>';
                                    }
                                    ?>
                                    <li>
                                        Tình trạng:
                                        <span style="margin-left: 0.25rem;"
                                              class="condition-option"><?= isset($product['condition']) && $product['condition'] !== null ? $product['condition'] : ''; ?></span>
                                    </li>
                                    <li>
                                        Tạm tính:
                                        <span style="margin-left: 0.25rem;"
                                              class="weight-option"><?= $product['weight']; ?></span> kg
                                    </li>
                                </ol>
                            </div>
                            <div class="right">
                                <div class="qty form-inline quantity-option">
                                    <label>Số lượng:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary button-quantity-down"
                                                    data-pjax="1"
                                                    data-id="<?= $product['parent_sku'] ?>"
                                                    data-sku="<?= $product['sku']; ?>"
                                                    data-update="#<?= $item['key']; ?>"
                                                    data-operator="down"
                                                    type="button">-
                                            </button>
                                        </div>
                                        <input type="text"
                                               name="cartItemQuantity" class="form-control"
                                               value="<?= $product['quantity']; ?>"
                                               data-min="1"
                                               data-max="<?= (int)(($max = $availableQuantity - $soldQuantity) <= 0 ? 0 : $max); ?>"
                                               data-parent="<?= $item['key'] ?>"
                                               data-id="<?= $product['parent_sku'] ?>"
                                               data-sku="<?= $product['sku']; ?>"
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary button-quantity-up"
                                                    data-pjax="1"
                                                    data-parent="<?= $item['key'] ?>"
                                                    data-id="<?= $product['parent_sku'] ?>"
                                                    data-sku="<?= $product['sku']; ?>"
                                                    data-operator="up"
                                                    type="button">+
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="price price-option">
                                    <?= $storeManager->showMoney($product['total_final_amount']); ?>
                                </div>
                                <a href="#" class="del delete-item"
                                   data-parent="<?= $item['key'] ?>"
                                   data-id="<?= $product['parent_sku'] ?>"
                                   data-sku="<?= $product['sku']; ?>"
                                <i class="far fa-trash-alt"></i> Xóa</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
</div>

