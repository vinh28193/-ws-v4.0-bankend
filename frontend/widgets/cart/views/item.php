<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\cart\CartSelection;
use frontend\widgets\cart\CartWidgetHepper;

/* @var yii\web\View $this */
/* @var array $items */

/** @var  $storeManager common\components\StoreManager */
$storeManager = Yii::$app->storeManager;
$totalAmount = 0;
?>
<div class="cart-box" style="border: none">
    <div class="title">Giỏ hàng của bạn <span>(<?= count($items); ?> items)</span></div>
    <?php foreach ($items as $item):
        $key = $item['_id'];
        $type = $item['type'];
        if (($order = ArrayHelper::getValue($item, 'value')) !== null):?>
            <?php
            $products = ArrayHelper::getValue($order, 'products', []);
            if ($selected = CartSelection::isExist(CartSelection::TYPE_SHOPPING, $key)) {
                $totalAmount += (int)$order['total_final_amount_local'];
            }
            ?>
            <ul class="cart-item" data-key="<?= $key ?>" data-type="<?= $type; ?>"
                style="border: 1px solid #e3e3e3;margin-bottom:10px">
                <li>
                    <?php $seller = ArrayHelper::getValue($order, 'seller', []); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="cartOrder" <?= $selected ? 'checked' : ''; ?>
                               value="<?= $key; ?>" id="cartOrder<?= $key; ?>">
                        <label class="form-check-label" for="cartOrder<?= $key; ?>">
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
                        <!--                        <div class="form-check">-->
                        <!--                            <input class="form-check-input"-->
                        <!--                                   type="checkbox"-->
                        <!--                                   name="cartProduct"-->
                        <!--                                   data-parent="--><? //= $key ?><!--"-->
                        <!--                                   data-id="--><? //= $product['parent_sku'] ?><!--"-->
                        <!--                                   data-sku="--><? //= $product['sku']; ?><!--"-->
                        <!--                                   id="-->
                        <? //= CartWidgetHepper::getCartProductId($product['parent_sku'], $product['sku']); ?><!--"-->
                        <!--                                --><? //= CartWidgetHepper::getIsChecked($key, $product['parent_sku'], $product['sku']) ? 'checked' : ''; ?>
                        <!--                            >-->
                        <!---->
                        <!--                            <label class="form-check-label"-->
                        <!--                                   for="-->
                        <? //= CartWidgetHepper::getCartProductId($product['parent_sku'], $product['sku']); ?><!--"></label>-->
                        <!--                        </div>-->
                        <div class="thumb" style="height: auto;">
                            <img src="<?= $product['link_img']; ?>"
                                 alt="<?= $product['product_name']; ?>" title="<?= $product['product_name']; ?>">
                        </div>
                        <div class="info">
                            <div class="left">
                                <a href="<?= $product['product_link']; ?>" target="_blank" class="name">
                                    <?= $product['product_name']; ?></a>
                                <div class="rate" style="text-align: left">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
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
                                              class="weight-option"><?= $product['total_weight_temporary']; ?></span> kg
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
                                                    data-type="<?= $type; ?>"
                                                    data-parent="<?= $key ?>"
                                                    data-id="<?= $product['parent_sku'] ?>"
                                                    data-sku="<?= $product['sku']; ?>"
                                                    data-update="#<?= $key; ?>"
                                                    data-operator="down"
                                                    type="button">-
                                            </button>
                                        </div>
                                        <input type="text"
                                               name="cartItemQuantity" class="form-control"
                                               value="<?= $product['quantity_customer']; ?>"
                                               data-min="1"
                                               data-type="<?= $type; ?>"
                                               data-max="<?= (int)(($max = $availableQuantity - $soldQuantity) <= 0 ? 0 : $max); ?>"
                                               data-parent="<?= $key ?>"
                                               data-id="<?= $product['parent_sku'] ?>"
                                               data-sku="<?= $product['sku']; ?>"
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary button-quantity-up"
                                                    data-pjax="1"
                                                    data-parent="<?= $key ?>"
                                                    data-type="<?= $type; ?>"
                                                    data-id="<?= $product['parent_sku'] ?>"
                                                    data-sku="<?= $product['sku']; ?>"
                                                    data-operator="up"
                                                    type="button">+
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="price price-option">
                                    <?= $storeManager->showMoney($product['total_price_amount_local']); ?>
                                </div>
                                <a href="#" class="del delete-item"
                                   data-type="<?= $type; ?>"
                                   data-parent="<?= $key ?>"
                                   data-id="<?= $product['parent_sku'] ?>"
                                   data-sku="<?= $product['sku']; ?>"
                                <i class="far fa-trash-alt"></i> Xóa</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; endforeach; ?>
    <ul class="billing" style="margin-top:-10px">
        <li>
            <div class="left">Giá trị đơn hàng:</div>
            <div class="right"><?= $storeManager->showMoney($totalAmount); ?></div>
        </li>
    </ul>
</div>
<p class="text-right note">* Quý khách nên thanh toán ngay để tránh sản phẩm bị tăng giá</p>
<div class="btn-box">
    <button type="button" class="btn btn-continue">Tiếp tục mua hàng</button>
    <button type="button" class="btn btn-payment">Tiến hành thanh toán</button>
</div>
